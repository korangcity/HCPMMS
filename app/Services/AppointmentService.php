<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

final class AppointmentService
{
    public function create(
        array $data,
    ): Appointment {
        return DB::transaction(
            function () use ($data): Appointment {
                return Appointment::query()->create($data);
            },
        );
    }

    public function update(
        Appointment $appointment,
        array $data,
    ): Appointment {
        return DB::transaction(
            function () use (
                $appointment,
                $data,
            ): Appointment {
                $appointment->update($data);

                return $appointment->refresh();
            },
        );
    }

    public function cancel(
        Appointment $appointment,
    ): Appointment {
        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
        ]);

        return $appointment->refresh();
    }

    public function complete(
        Appointment $appointment,
    ): Appointment {
        $appointment->update([
            'status' => AppointmentStatus::Completed,
        ]);

        return $appointment->refresh();
    }
}
