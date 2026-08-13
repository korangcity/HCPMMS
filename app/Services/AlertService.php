<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AlertStatus;
use App\Enums\AlertType;
use App\Enums\VitalSignType;
use App\Events\AlertCreated;
use App\Models\Alert;
use App\Models\AlertRecipient;
use App\Models\AlertRule;
use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSign;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class AlertService
{
    public function processVitalSign(VitalSign $vitalSign): ?Alert
    {
        $vitalSign->loadMissing('patient');

        $type = $vitalSign->type instanceof VitalSignType
            ? $vitalSign->type
            : VitalSignType::from($vitalSign->type);

        $rule = AlertRule::query()
            ->active()
            ->forVitalType($type)
            ->orderByDesc('priority')
            ->first();

        if ($rule === null) {
            return null;
        }

        $value = (float) $vitalSign->value;

        if (! $rule->matches($value)) {
            return null;
        }

        $deduplicationKey = $this->makeDeduplicationKey(
            patientId: $vitalSign->patient_id,
            vitalType: $type,
            ruleId: $rule->id,
        );

        $existingAlert = Alert::query()
            ->where('deduplication_key', $deduplicationKey)
            ->whereIn('status', [
                AlertStatus::Open->value,
                AlertStatus::Acknowledged->value,
            ])
            ->where(
                'triggered_at',
                '>=',
                now()->subMinutes($rule->deduplication_minutes),
            )
            ->latest('triggered_at')
            ->first();

        if ($existingAlert !== null) {
            return $existingAlert;
        }

        $alert = DB::transaction(function () use (
            $vitalSign,
            $rule,
            $deduplicationKey,
            $type,
        ): Alert {
            $alert = Alert::query()->create([
                'patient_id' => $vitalSign->patient_id,
                'vital_sign_id' => $vitalSign->id,
                'alert_rule_id' => $rule->id,

                'type' => AlertType::AbnormalVitalSign,

                'priority' => $rule->priority,

                'status' => AlertStatus::Open,

                'title' => sprintf(
                    'مقدار غیرعادی %s',
                    $type->label(),
                ),

                'message' => $this->makeMessage(
                    $type,
                    (float) $vitalSign->value,
                    $rule,
                ),

                'observed_value' => $vitalSign->value,
                'expected_min' => $rule->min_value,
                'expected_max' => $rule->max_value,
                'unit' => $vitalSign->unit,

                'triggered_at' => $vitalSign->recorded_at ?? now(),

                'deduplication_key' => $deduplicationKey,
            ]);

            $this->createRecipients($alert);

            return $alert;
        });

        AlertCreated::dispatch($alert);

        return $alert;
    }

    public function acknowledge(
        Alert $alert,
        User $user,
    ): Alert {
        $alert->update([
            'status' => AlertStatus::Acknowledged,
            'acknowledged_at' => now(),
            'acknowledged_by' => $user->id,
        ]);

        return $alert->refresh();
    }

    public function resolve(
        Alert $alert,
        User $user,
        ?string $note = null,
    ): Alert {
        $alert->update([
            'status' => AlertStatus::Resolved,
            'resolved_at' => now(),
            'resolved_by' => $user->id,
            'resolution_note' => $note,
        ]);

        return $alert->refresh();
    }

    private function createRecipients(Alert $alert): void
    {
        $alert->loadMissing('patient');

        /** @var Collection<int, User> $doctors */
        $doctors = $alert->patient
            ->doctors()
            ->get();

        /** @var Collection<int, User> $caregivers */
        $caregivers = $alert->patient
            ->caregivers()
            ->get();

        foreach ($doctors as $doctor) {
            AlertRecipient::query()->firstOrCreate(
                [
                    'alert_id' => $alert->id,
                    'user_id' => $doctor->id,
                ],
                [
                    'recipient_type' => 'doctor',
                ],
            );
        }

        foreach ($caregivers as $caregiver) {
            AlertRecipient::query()->firstOrCreate(
                [
                    'alert_id' => $alert->id,
                    'user_id' => $caregiver->id,
                ],
                [
                    'recipient_type' => 'caregiver',
                ],
            );
        }
    }

    private function makeMessage(
        VitalSignType $type,
        float $value,
        AlertRule $rule,
    ): string {
        $range = [];

        if ($rule->min_value !== null) {
            $range[] = 'حداقل ' . $rule->min_value;
        }

        if ($rule->max_value !== null) {
            $range[] = 'حداکثر ' . $rule->max_value;
        }

        return sprintf(
            '%s با مقدار %s ثبت شده است. محدوده تعریف‌شده: %s.',
            $type->label(),
            $value,
            implode(' و ', $range),
        );
    }

    private function makeDeduplicationKey(
        int $patientId,
        VitalSignType $vitalType,
        int $ruleId,
    ): string {
        return hash(
            'sha256',
            implode('|', [
                $patientId,
                $vitalType->value,
                $ruleId,
            ]),
        );
    }
}
