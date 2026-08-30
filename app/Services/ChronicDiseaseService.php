<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ChronicDisease;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ChronicDiseaseService
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): ChronicDisease
    {
        return DB::transaction(
            function () use ($data): ChronicDisease {
                $chronicDisease = ChronicDisease::create($data);

                return $chronicDisease->fresh([
                    'patient.user',
                ]);
            },
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(
        ChronicDisease $chronicDisease,
        array $data,
    ): ChronicDisease {
        return DB::transaction(
            function () use (
                $chronicDisease,
                $data,
            ): ChronicDisease {
                $chronicDisease->update($data);

                return $chronicDisease->fresh([
                    'patient.user',
                ]);
            },
        );
    }

    public function delete(ChronicDisease $chronicDisease): void
    {
        DB::transaction(
            function () use ($chronicDisease): void {
                $chronicDisease->delete();
            },
        );
    }

    public function find(
        int $id,
    ): ?ChronicDisease {
        return ChronicDisease::query()
            ->with([
                'patient.user',
                'healthRecords',
            ])
            ->find($id);
    }

    /**
     * @return Collection<int, ChronicDisease>
     */
    public function forPatient(
        int $patientId,
    ): Collection {
        return ChronicDisease::query()
            ->where('patient_id', $patientId)
            ->with([
                'healthRecords',
            ])
            ->orderByDesc('diagnosed_at')
            ->get();
    }
}
