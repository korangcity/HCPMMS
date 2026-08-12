<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\PatientRelationStatus;
use App\Models\Patient;
use Illuminate\Console\Command;

final class DeactivateExpiredPatientRelations extends Command
{
    protected $signature = 'patients:deactivate-expired-relations';

    protected $description = 'Deactivate expired doctor and caregiver relationships.';

    public function handle(): int
    {
        $today = now()->toDateString();

        Patient::query()
            ->with(['doctors', 'caregivers'])
            ->chunkById(100, function ($patients) use ($today): void {
                foreach ($patients as $patient) {
                    foreach ($patient->doctors as $doctor) {
                        if (
                            $doctor->pivot->ended_at !== null
                            && $doctor->pivot->ended_at <= $today
                        ) {
                            $patient->doctors()->updateExistingPivot(
                                $doctor->id,
                                [
                                    'status' => PatientRelationStatus::Inactive,
                                ],
                            );
                        }
                    }

                    foreach ($patient->caregivers as $caregiver) {
                        if (
                            $caregiver->pivot->ended_at !== null
                            && $caregiver->pivot->ended_at <= $today
                        ) {
                            $patient->caregivers()->updateExistingPivot(
                                $caregiver->id,
                                [
                                    'status' => PatientRelationStatus::Inactive,
                                ],
                            );
                        }
                    }
                }
            });

        $this->info('Expired patient relationships deactivated.');

        return self::SUCCESS;
    }
}
