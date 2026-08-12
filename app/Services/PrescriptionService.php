<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PrescriptionStatus;
use App\Events\PrescriptionCreated;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\DB;

final class PrescriptionService
{
    /**
     * @param array{
     *     patient_id:int,
     *     doctor_id:int,
     *     status?:PrescriptionStatus|string,
     *     prescribed_at:string,
     *     valid_from?:string|null,
     *     valid_until?:string|null,
     *     notes?:string|null,
     *     items:array<int,array{
     *         medication_id:int,
     *         dose:numeric,
     *         dose_unit:string,
     *         route?:string|null,
     *         quantity?:int|null,
     *         duration_days?:int|null,
     *         instructions?:string|null,
     *         schedules?:array<int,array{
     *             frequency:string,
     *             scheduled_time:string,
     *             interval_hours?:int|null,
     *             starts_at:string,
     *             ends_at?:string|null,
     *             is_active?:bool,
     *             notes?:string|null
     *         }>
     *     }>
     * } $data
     */
    public function create(array $data): Prescription
    {
        return DB::transaction(function () use ($data): Prescription {
            $status = $data['status'] ?? PrescriptionStatus::Active;

            if (is_string($status)) {
                $status = PrescriptionStatus::from($status);
            }

            $prescription = Prescription::query()->create([
                'patient_id' => $data['patient_id'],
                'doctor_id' => $data['doctor_id'],
                'status' => $status,
                'prescribed_at' => $data['prescribed_at'],
                'valid_from' => $data['valid_from'] ?? null,
                'valid_until' => $data['valid_until'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $itemData) {
                $item = $prescription->items()->create([
                    'medication_id' => $itemData['medication_id'],
                    'dose' => $itemData['dose'],
                    'dose_unit' => $itemData['dose_unit'],
                    'route' => $itemData['route'] ?? null,
                    'quantity' => $itemData['quantity'] ?? null,
                    'duration_days' => $itemData['duration_days'] ?? null,
                    'instructions' => $itemData['instructions'] ?? null,
                ]);

                foreach ($itemData['schedules'] ?? [] as $schedule) {
                    $item->schedules()->create([
                        'frequency' => $schedule['frequency'],
                        'scheduled_time' => $schedule['scheduled_time'],
                        'interval_hours' => $schedule['interval_hours'] ?? null,
                        'starts_at' => $schedule['starts_at'],
                        'ends_at' => $schedule['ends_at'] ?? null,
                        'is_active' => $schedule['is_active'] ?? true,
                        'notes' => $schedule['notes'] ?? null,
                    ]);
                }
            }

            $prescription->load([
                'patient',
                'doctor',
                'items.medication',
                'items.schedules',
            ]);

            PrescriptionCreated::dispatch($prescription);

            return $prescription;
        });
    }

    public function activate(Prescription $prescription): void
    {
        $prescription->update([
            'status' => PrescriptionStatus::Active,
        ]);

        $prescription->items()
            ->with('schedules')
            ->get()
            ->each(function (PrescriptionItem $item): void {
                $item->schedules()
                    ->update(['is_active' => true]);
            });
    }

    public function cancel(Prescription $prescription): void
    {
        $prescription->update([
            'status' => PrescriptionStatus::Cancelled,
        ]);

        $prescription->items()
            ->with('schedules')
            ->get()
            ->each(function (PrescriptionItem $item): void {
                $item->schedules()
                    ->update(['is_active' => false]);
            });
    }
}
