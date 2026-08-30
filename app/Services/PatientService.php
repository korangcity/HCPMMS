<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PatientStatus;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

final class PatientService
{
    public function create(
        array $data,
        array $doctorIds = [],
        array $caregiverIds = []
    ): Patient {
        return DB::transaction(function () use (
            $data,
            $doctorIds,
            $caregiverIds
        ): Patient {
            $patient = Patient::query()->create($data);

            if ($doctorIds !== []) {
                $patient->doctors()->sync(
                    $this->doctorSyncData($doctorIds)
                );
            }

            if ($caregiverIds !== []) {
                $patient->caregivers()->sync(
                    $this->caregiverSyncData($caregiverIds)
                );
            }

            return $patient->load([
                'user',
                'doctors',
                'caregivers',
            ]);
        });
    }

    public function update(
        Patient $patient,
        array $data,
        ?array $doctorIds = null,
        ?array $caregiverIds = null
    ): Patient {
        return DB::transaction(function () use (
            $patient,
            $data,
            $doctorIds,
            $caregiverIds
        ): Patient {
            $patient->update($data);

            if ($doctorIds !== null) {
                $patient->doctors()->sync(
                    $this->doctorSyncData($doctorIds)
                );
            }

            if ($caregiverIds !== null) {
                $patient->caregivers()->sync(
                    $this->caregiverSyncData($caregiverIds)
                );
            }

            return $patient->refresh()->load([
                'user',
                'doctors',
                'caregivers',
            ]);
        });
    }

    public function assignDoctor(
        Patient $patient,
        int $doctorId,
        ?string $notes = null
    ): Patient {
        $patient->doctors()->syncWithoutDetaching([
            $doctorId => [
                'assigned_at' => now(),
                'notes' => $notes,
            ],
        ]);

        return $patient->refresh();
    }

    public function removeDoctor(
        Patient $patient,
        int $doctorId
    ): Patient {
        $patient->doctors()->detach($doctorId);

        return $patient->refresh();
    }

    public function assignCaregiver(
        Patient $patient,
        int $caregiverId,
        bool $isPrimary = false,
        ?string $notes = null
    ): Patient {
        $patient->caregivers()->syncWithoutDetaching([
            $caregiverId => [
                'is_primary' => $isPrimary,
                'notes' => $notes,
            ],
        ]);

        return $patient->refresh();
    }

    public function removeCaregiver(
        Patient $patient,
        int $caregiverId
    ): Patient {
        $patient->caregivers()->detach($caregiverId);

        return $patient->refresh();
    }

    public function activate(Patient $patient): Patient
    {
        $patient->update([
            'status' => PatientStatus::ACTIVE,
        ]);

        return $patient->refresh();
    }

    public function deactivate(Patient $patient): Patient
    {
        $patient->update([
            'status' => PatientStatus::INACTIVE,
        ]);

        return $patient->refresh();
    }

    public function delete(Patient $patient): void
    {
        DB::transaction(function () use ($patient): void {
            $patient->doctors()->detach();
            $patient->caregivers()->detach();

            $patient->delete();
        });
    }

    /**
     * @param array<int, int|string> $doctorIds
     * @return array<int, array<string, mixed>>
     */
    private function doctorSyncData(array $doctorIds): array
    {
        $data = [];

        foreach ($doctorIds as $doctorId) {
            $data[(int) $doctorId] = [
                'assigned_at' => now(),
            ];
        }

        return $data;
    }

    /**
     * @param array<int, int|string> $caregiverIds
     * @return array<int, array<string, mixed>>
     */
    private function caregiverSyncData(array $caregiverIds): array
    {
        $data = [];

        foreach ($caregiverIds as $caregiverId) {
            $data[(int) $caregiverId] = [
                'is_primary' => false,
            ];
        }

        return $data;
    }
}
