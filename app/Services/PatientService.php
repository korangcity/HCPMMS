<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PatientRelationStatus;
use App\Models\ChronicDisease;
use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class PatientService
{
    public function create(array $data): Patient
    {
        return DB::transaction(function () use ($data): Patient {
            return Patient::create($data);
        });
    }

    public function update(Patient $patient, array $data): Patient
    {
        return DB::transaction(function () use ($patient, $data): Patient {
            $patient->update($data);

            return $patient->refresh();
        });
    }

    public function attachChronicDisease(
        Patient $patient,
        ChronicDisease $disease,
        array $data = [],
    ): void {
        $patient->chronicDiseases()->syncWithoutDetaching([
            $disease->id => [
                'diagnosed_at' => $data['diagnosed_at'] ?? null,
                'notes' => $data['notes'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ],
        ]);
    }

    public function detachChronicDisease(
        Patient $patient,
        ChronicDisease $disease,
    ): void {
        $patient->chronicDiseases()->detach($disease->id);
    }

    public function addHealthRecord(
        Patient $patient,
        User $creator,
        array $data,
    ): HealthRecord {
        return DB::transaction(function () use (
            $patient,
            $creator,
            $data,
        ): HealthRecord {
            return $patient->healthRecords()->create([
                ...$data,
                'created_by' => $creator->id,
            ]);
        });
    }

    public function assignDoctor(
        Patient $patient,
        User $doctor,
        array $data = [],
    ): void {
        $patient->doctors()->syncWithoutDetaching([
            $doctor->id => [
                'status' => PatientRelationStatus::Active,
                'started_at' => $data['started_at'] ?? now()->toDateString(),
                'ended_at' => null,
                'notes' => $data['notes'] ?? null,
            ],
        ]);
    }

    public function removeDoctor(
        Patient $patient,
        User $doctor,
    ): void {
        $patient->doctors()->updateExistingPivot(
            $doctor->id,
            [
                'status' => PatientRelationStatus::Inactive,
                'ended_at' => now()->toDateString(),
            ],
        );
    }

    public function assignCaregiver(
        Patient $patient,
        User $caregiver,
        array $data = [],
    ): void {
        $patient->caregivers()->syncWithoutDetaching([
            $caregiver->id => [
                'status' => PatientRelationStatus::Active,
                'started_at' => $data['started_at'] ?? now()->toDateString(),
                'ended_at' => null,
                'notes' => $data['notes'] ?? null,
            ],
        ]);
    }

    public function removeCaregiver(
        Patient $patient,
        User $caregiver,
    ): void {
        $patient->caregivers()->updateExistingPivot(
            $caregiver->id,
            [
                'status' => PatientRelationStatus::Inactive,
                'ended_at' => now()->toDateString(),
            ],
        );
    }
}
