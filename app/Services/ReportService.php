<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Patient;
use App\Models\Report;
use App\Models\VitalSign;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class ReportService
{
    public function generate(
        Patient $patient,
        ReportType $type,
        Carbon $from,
        Carbon $to,
        ?User $generatedBy = null,
    ): Report {
        if ($from->greaterThan($to)) {
            throw new InvalidArgumentException(
                'The report start date must be before the end date.'
            );
        }

        $report = Report::query()->create([
            'patient_id' => $patient->id,
            'generated_by' => $generatedBy?->id,
            'type' => $type,
            'status' => ReportStatus::Processing,
            'from' => $from,
            'to' => $to,
        ]);

        try {
            $data = match ($type) {
                ReportType::BloodPressure => $this->bloodPressure(
                    $patient,
                    $from,
                    $to
                ),

                ReportType::BloodGlucose => $this->bloodGlucose(
                    $patient,
                    $from,
                    $to
                ),

                ReportType::Weight => $this->weight(
                    $patient,
                    $from,
                    $to
                ),

                ReportType::VitalSigns => $this->vitalSigns(
                    $patient,
                    $from,
                    $to
                ),

                ReportType::TimeRange => $this->timeRange(
                    $patient,
                    $from,
                    $to
                ),
            };

            $report->update([
                'status' => ReportStatus::Completed,
                'data' => $data,
                'generated_at' => now(),
            ]);

            return $report->refresh();
        } catch (\Throwable $exception) {
            $report->update([
                'status' => ReportStatus::Failed,
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function bloodPressure(
        Patient $patient,
        Carbon $from,
        Carbon $to
    ): array {
        $records = $this->vitalSigns(
            $patient,
            $from,
            $to,
            ['systolic', 'diastolic']
        );

        $items = collect($records['records']);

        return [
            'type' => ReportType::BloodPressure->value,
            'label' => ReportType::BloodPressure->label(),
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'count' => $items->count(),

            'systolic' => [
                'min' => $items->min('systolic'),
                'max' => $items->max('systolic'),
                'average' => $this->average($items, 'systolic'),
            ],

            'diastolic' => [
                'min' => $items->min('diastolic'),
                'max' => $items->max('diastolic'),
                'average' => $this->average($items, 'diastolic'),
            ],

            'records' => $records['records'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function bloodGlucose(
        Patient $patient,
        Carbon $from,
        Carbon $to
    ): array {
        $records = VitalSign::query()
            ->where('patient_id', $patient->id)
            ->whereBetween('measured_at', [$from, $to])
            ->whereNotNull('blood_glucose')
            ->orderBy('measured_at')
            ->get([
                'id',
                'blood_glucose',
                'measured_at',
            ]);

        $values = $records->pluck('blood_glucose');

        return [
            'type' => ReportType::BloodGlucose->value,
            'label' => ReportType::BloodGlucose->label(),
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'count' => $records->count(),

            'summary' => [
                'min' => $values->min(),
                'max' => $values->max(),
                'average' => $values->isNotEmpty()
                    ? round((float) $values->avg(), 2)
                    : null,
            ],

            'records' => $records->map(
                static fn (VitalSign $record): array => [
                    'id' => $record->id,
                    'value' => $record->blood_glucose,
                    'measured_at' => $record->measured_at?->toIso8601String(),
                ]
            )->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function weight(
        Patient $patient,
        Carbon $from,
        Carbon $to
    ): array {
        $records = VitalSign::query()
            ->where('patient_id', $patient->id)
            ->whereBetween('measured_at', [$from, $to])
            ->whereNotNull('weight')
            ->orderBy('measured_at')
            ->get([
                'id',
                'weight',
                'measured_at',
            ]);

        $values = $records->pluck('weight');

        $first = $records->first();
        $last = $records->last();

        return [
            'type' => ReportType::Weight->value,
            'label' => ReportType::Weight->label(),
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'count' => $records->count(),

            'summary' => [
                'min' => $values->min(),
                'max' => $values->max(),
                'average' => $values->isNotEmpty()
                    ? round((float) $values->avg(), 2)
                    : null,

                'first' => $first?->weight,
                'last' => $last?->weight,

                'change' => $first !== null && $last !== null
                    ? round(
                        (float) $last->weight -
                        (float) $first->weight,
                        2
                    )
                    : null,
            ],

            'records' => $records->map(
                static fn (VitalSign $record): array => [
                    'id' => $record->id,
                    'value' => $record->weight,
                    'measured_at' => $record->measured_at?->toIso8601String(),
                ]
            )->values()->all(),
        ];
    }

    /**
     * @param array<int, string>|null $columns
     * @return array<string, mixed>
     */
    private function vitalSigns(
        Patient $patient,
        Carbon $from,
        Carbon $to,
        ?array $columns = null,
    ): array {
        $select = [
            'id',
            'patient_id',
            'systolic',
            'diastolic',
            'blood_glucose',
            'weight',
            'heart_rate',
            'respiratory_rate',
            'temperature',
            'oxygen_saturation',
            'measured_at',
        ];

        if ($columns !== null) {
            $select = array_unique([
                'id',
                'measured_at',
                ...$columns,
            ]);
        }

        $records = VitalSign::query()
            ->where('patient_id', $patient->id)
            ->whereBetween('measured_at', [$from, $to])
            ->orderBy('measured_at')
            ->get($select);

        return [
            'type' => ReportType::VitalSigns->value,
            'label' => ReportType::VitalSigns->label(),
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'count' => $records->count(),

            'records' => $records->map(
                static fn (VitalSign $record): array => [
                    'id' => $record->id,
                    'systolic' => $record->systolic,
                    'diastolic' => $record->diastolic,
                    'blood_glucose' => $record->blood_glucose,
                    'weight' => $record->weight,
                    'heart_rate' => $record->heart_rate,
                    'respiratory_rate' => $record->respiratory_rate,
                    'temperature' => $record->temperature,
                    'oxygen_saturation' => $record->oxygen_saturation,
                    'measured_at' => $record->measured_at?->toIso8601String(),
                ]
            )->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function timeRange(
        Patient $patient,
        Carbon $from,
        Carbon $to
    ): array {
        $records = VitalSign::query()
            ->where('patient_id', $patient->id)
            ->whereBetween('measured_at', [$from, $to])
            ->orderBy('measured_at')
            ->get();

        return [
            'type' => ReportType::TimeRange->value,
            'label' => ReportType::TimeRange->label(),
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'count' => $records->count(),

            'summary' => [
                'blood_pressure_count' => $records
                    ->whereNotNull('systolic')
                    ->whereNotNull('diastolic')
                    ->count(),

                'blood_glucose_count' => $records
                    ->whereNotNull('blood_glucose')
                    ->count(),

                'weight_count' => $records
                    ->whereNotNull('weight')
                    ->count(),

                'heart_rate_count' => $records
                    ->whereNotNull('heart_rate')
                    ->count(),

                'temperature_count' => $records
                    ->whereNotNull('temperature')
                    ->count(),

                'oxygen_saturation_count' => $records
                    ->whereNotNull('oxygen_saturation')
                    ->count(),
            ],

            'records' => $records->map(
                static fn (VitalSign $record): array => [
                    'id' => $record->id,
                    'measured_at' => $record->measured_at?->toIso8601String(),
                    'systolic' => $record->systolic,
                    'diastolic' => $record->diastolic,
                    'blood_glucose' => $record->blood_glucose,
                    'weight' => $record->weight,
                    'heart_rate' => $record->heart_rate,
                    'respiratory_rate' => $record->respiratory_rate,
                    'temperature' => $record->temperature,
                    'oxygen_saturation' => $record->oxygen_saturation,
                ]
            )->values()->all(),
        ];
    }

    private function average(
        \Illuminate\Support\Collection $items,
        string $key
    ): ?float {
        $values = $items
            ->pluck($key)
            ->filter(static fn ($value): bool => $value !== null);

        return $values->isEmpty()
            ? null
            : round((float) $values->avg(), 2);
    }

    /**
     * @return Collection<int, Report>
     */
    public function forPatient(
        Patient $patient,
        ?ReportType $type = null,
        ?Carbon $from = null,
        ?Carbon $to = null,
    ): Collection {
        return Report::query()
            ->with([
                'patient',
                'generatedBy',
            ])
            ->where('patient_id', $patient->id)
            ->when(
                $type,
                fn ($query) => $query->where('type', $type)
            )
            ->when(
                $from,
                fn ($query) => $query->where('from', '>=', $from)
            )
            ->when(
                $to,
                fn ($query) => $query->where('to', '<=', $to)
            )
            ->latest()
            ->get();
    }
}
