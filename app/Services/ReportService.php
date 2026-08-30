<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\HealthRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class ReportService
{
    public function query(array $filters): Builder
    {
        $query = HealthRecord::query()
            ->with([
                'patient.user',
                'chronicDisease',
                'recorder',
                'appointment',
                'visit',
            ]);

        $this->applyFilters(
            $query,
            $filters,
        );

        return $query;
    }

    public function records(
        array $filters,
    ): Collection {
        return $this->query($filters)
            ->orderBy('created_at')
            ->get();
    }

    public function count(
        array $filters,
    ): int {
        return $this->query($filters)->count();
    }

    public function measurementCount(
        array $filters,
        string $type,
    ): int {
        $records = $this->records($filters);

        return $records->sum(
            fn (HealthRecord $record): int => collect(
                $record->measurement_information ?? [],
            )
                ->where('type', $type)
                ->count(),
        );
    }

    public function measurements(
        array $filters,
        string $type,
    ): Collection {
        $records = $this->records($filters);

        return $records
            ->flatMap(
                function (
                    HealthRecord $record,
                ) use ($type): Collection {
                    return collect(
                        $record->measurement_information ?? [],
                    )
                        ->filter(
                            fn (
                                array $measurement,
                            ): bool => ($measurement['type'] ?? null)
                                === $type,
                        )
                        ->map(
                            function (
                                array $measurement,
                            ) use ($record): array {
                                return [
                                    'patient' => $record
                                        ->patient
                                        ?->user
                                        ?->name,

                                    'recorded_at' =>
                                        $measurement['recorded_at']
                                        ?? null,

                                    'value' =>
                                        $measurement['value']
                                        ?? null,

                                    'unit' =>
                                        $measurement['unit']
                                        ?? null,
                                ];
                            },
                        );
                },
            )
            ->filter(
                fn (array $item): bool => filled(
                    $item['value'] ?? null,
                ),
            )
            ->values();
    }

    private function applyFilters(
        Builder $query,
        array $filters,
    ): void {
        $query
            ->when(
                $filters['patient_id'] ?? null,
                fn (
                    Builder $query,
                    int|string $patientId,
                ): Builder => $query->where(
                    'patient_id',
                    $patientId,
                ),
            )
            ->when(
                $filters['doctor_id'] ?? null,
                fn (
                    Builder $query,
                    int|string $doctorId,
                ): Builder => $query->whereHas(
                    'recorder',
                    fn (
                        Builder $query,
                    ): Builder => $query
                        ->whereHas(
                            'doctor',
                            fn (
                                Builder $query,
                            ): Builder => $query->where(
                                'id',
                                $doctorId,
                            ),
                        ),
                ),
            )
            ->when(
                $filters['chronic_disease_id'] ?? null,
                fn (
                    Builder $query,
                    int|string $diseaseId,
                ): Builder => $query->where(
                    'chronic_disease_id',
                    $diseaseId,
                ),
            )
            ->when(
                $filters['appointment_id'] ?? null,
                fn (
                    Builder $query,
                    int|string $appointmentId,
                ): Builder => $query->where(
                    'appointment_id',
                    $appointmentId,
                ),
            )
            ->when(
                $filters['visit_id'] ?? null,
                fn (
                    Builder $query,
                    int|string $visitId,
                ): Builder => $query->where(
                    'visit_id',
                    $visitId,
                ),
            )
            ->when(
                $filters['date_from'] ?? null,
                fn (
                    Builder $query,
                    string $date,
                ): Builder => $query->where(
                    'created_at',
                    '>=',
                    $date . ' 00:00:00',
                ),
            )
            ->when(
                $filters['date_to'] ?? null,
                fn (
                    Builder $query,
                    string $date,
                ): Builder => $query->where(
                    'created_at',
                    '<=',
                    $date . ' 23:59:59',
                ),
            );
    }
}
