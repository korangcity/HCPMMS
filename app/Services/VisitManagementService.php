<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Events\AppointmentCancelled;
use App\Events\AppointmentScheduled;
use App\Events\VisitCompleted;
use App\Models\Appointment;
use App\Models\DoctorNote;
use App\Models\FollowUp;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class VisitManagementService
{
    public function scheduleAppointment(
        int $patientId,
        int $doctorId,
        \DateTimeInterface $scheduledAt,
        int $durationMinutes,
        ?string $reason = null,
        ?string $patientNote = null,
    ): Appointment {
        return DB::transaction(function () use (
            $patientId,
            $doctorId,
            $scheduledAt,
            $durationMinutes,
            $reason,
            $patientNote
        ): Appointment {
            $endAt = \Carbon\CarbonImmutable::instance(
                $scheduledAt
            )->addMinutes($durationMinutes);

            $hasConflict = Appointment::query()
                ->where('doctor_id', $doctorId)
                ->whereIn('status', [
                    AppointmentStatus::Scheduled,
                    AppointmentStatus::Confirmed,
                ])
                ->whereBetween(
                    'scheduled_at',
                    [
                        $scheduledAt,
                        $endAt,
                    ]
                )
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'scheduled_at' => 'پزشک در این بازه زمانی نوبت دیگری دارد.',
                ]);
            }

            $appointment = Appointment::query()->create([
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'scheduled_at' => $scheduledAt,
                'duration_minutes' => $durationMinutes,
                'status' => AppointmentStatus::Scheduled,
                'reason' => $reason,
                'patient_note' => $patientNote,
            ]);

            AppointmentScheduled::dispatch($appointment);

            return $appointment;
        });
    }

    public function confirmAppointment(
        Appointment $appointment
    ): Appointment {
        if ($appointment->status !== AppointmentStatus::Scheduled) {
            throw ValidationException::withMessages([
                'status' => 'این نوبت قابل تأیید نیست.',
            ]);
        }

        $appointment->update([
            'status' => AppointmentStatus::Confirmed,
            'confirmed_at' => now(),
        ]);

        return $appointment->refresh();
    }

    public function cancelAppointment(
        Appointment $appointment,
        string $reason
    ): Appointment {
        if (!$appointment->isCancellable()) {
            throw ValidationException::withMessages([
                'status' => 'این نوبت قابل لغو نیست.',
            ]);
        }

        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        AppointmentCancelled::dispatch($appointment);

        return $appointment->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function completeVisit(
        Appointment $appointment,
        array $data
    ): Visit {
        return DB::transaction(function () use (
            $appointment,
            $data
        ): Visit {
            if (
                !in_array(
                    $appointment->status,
                    [
                        AppointmentStatus::Scheduled,
                        AppointmentStatus::Confirmed,
                    ],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'appointment' => 'این نوبت امکان ثبت ویزیت ندارد.',
                ]);
            }

            if ($appointment->visit()->exists()) {
                throw ValidationException::withMessages([
                    'appointment' => 'برای این نوبت قبلاً ویزیت ثبت شده است.',
                ]);
            }

            $visit = Visit::query()->create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'visited_at' => $data['visited_at'] ?? now(),
                'chief_complaint' => $data['chief_complaint'] ?? null,
                'diagnosis' => $data['diagnosis'] ?? null,
                'clinical_summary' => $data['clinical_summary'] ?? null,
                'treatment_plan' => $data['treatment_plan'] ?? null,
                'patient_instructions' => $data['patient_instructions'] ?? null,
                'private_notes' => $data['private_notes'] ?? null,
            ]);

            $appointment->update([
                'status' => AppointmentStatus::Completed,
            ]);

            VisitCompleted::dispatch($visit);

            return $visit->load([
                'appointment',
                'patient',
                'doctor',
            ]);
        });
    }

    public function addDoctorNote(
        Visit $visit,
        int $doctorId,
        string $content,
        ?string $title = null,
        bool $isPrivate = false,
    ): DoctorNote {
        if ($visit->doctor_id !== $doctorId) {
            throw ValidationException::withMessages([
                'doctor_id' => 'این پزشک متعلق به این ویزیت نیست.',
            ]);
        }

        return $visit->doctorNotes()->create([
            'doctor_id' => $doctorId,
            'title' => $title,
            'content' => $content,
            'is_private' => $isPrivate,
        ]);
    }

    public function createFollowUp(
        Visit $visit,
        int $doctorId,
        \DateTimeInterface $dueAt,
        string $title,
        string $type,
        ?string $instructions = null,
    ): FollowUp {
        if ($visit->doctor_id !== $doctorId) {
            throw ValidationException::withMessages([
                'doctor_id' => 'این پزشک متعلق به این ویزیت نیست.',
            ]);
        }

        return $visit->followUps()->create([
            'patient_id' => $visit->patient_id,
            'doctor_id' => $doctorId,
            'type' => $type,
            'due_at' => $dueAt,
            'title' => $title,
            'instructions' => $instructions,
        ]);
    }

    public function completeFollowUp(
        FollowUp $followUp,
        ?string $notes = null
    ): FollowUp {
        if ($followUp->status->value === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'پیگیری لغوشده قابل تکمیل نیست.',
            ]);
        }

        $followUp->complete($notes);

        return $followUp->refresh();
    }
}
