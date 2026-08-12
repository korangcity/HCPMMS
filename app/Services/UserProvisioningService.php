<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CaregiverType;
use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Events\UserProvisioned;
use App\Models\Caregiver;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class UserProvisioningService
{
    /**
     * @param array{
     *     name:string,
     *     email:string,
     *     phone?:string|null,
     *     password:string,
     *     status?:UserStatus|string,
     *     role:string,
     *     profile?:array<string,mixed>
     * } $data
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'status' => $data['status'] ?? UserStatus::Active,
            ]);

            $role = $data['role'];

            if (! in_array(
                $role,
                ['admin', 'doctor', 'caregiver', 'patient'],
                true
            )) {
                throw ValidationException::withMessages([
                    'role' => 'Invalid user role.',
                ]);
            }

            $user->assignRole($role);

            $profile = $data['profile'] ?? [];

            match ($role) {
                'patient' => $this->createPatient($user, $profile),
                'doctor' => $this->createDoctor($user, $profile),
                'caregiver' => $this->createCaregiver($user, $profile),
                'admin' => null,
            };

            UserProvisioned::dispatch($user);

            return $user->load([
                'roles',
                'patient',
                'doctor',
                'caregiver',
            ]);
        });
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createPatient(User $user, array $data): Patient
    {
        return Patient::create([
            'user_id' => $user->id,
            'medical_record_number' => $data['medical_record_number'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => isset($data['gender'])
                ? Gender::from($data['gender'])
                : null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createDoctor(User $user, array $data): Doctor
    {
        return Doctor::create([
            'user_id' => $user->id,
            'medical_license_number' => $data['medical_license_number'],
            'specialty' => $data['specialty'],
            'bio' => $data['bio'] ?? null,
            'is_available' => $data['is_available'] ?? true,
        ]);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createCaregiver(
        User $user,
        array $data
    ): Caregiver {
        return Caregiver::create([
            'user_id' => $user->id,
            'type' => CaregiverType::from($data['type']),
            'national_identifier' => $data['national_identifier'] ?? null,
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
