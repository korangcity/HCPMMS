<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolePermissionSeeder extends Seeder
{
    private const GUARD = 'web';

    public function run(): void
    {
        $permissions = $this->createPermissions();

        $this->createRoles($permissions);
    }

    /**
     * @return array<string, Permission>
     */
    private function createPermissions(): array
    {
        $permissionNames = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'patients.view',
            'patients.create',
            'patients.update',
            'patients.delete',

            'doctors.view',
            'doctors.create',
            'doctors.update',
            'doctors.delete',

            'caregivers.view',
            'caregivers.create',
            'caregivers.update',
            'caregivers.delete',

            'health-records.view',
            'health-records.create',
            'health-records.update',
            'health-records.delete',

            'medications.view',
            'medications.create',
            'medications.update',
            'medications.delete',

            'reminders.view',
            'reminders.create',
            'reminders.update',
            'reminders.delete',

            'alerts.view',
            'alerts.create',
            'alerts.update',
            'alerts.delete',

            'appointments.view',
            'appointments.create',
            'appointments.update',
            'appointments.delete',

            'visits.view',
            'visits.create',
            'visits.update',
            'visits.delete',

            'reports.view',
            'reports.create',
        ];

        $permissions = [];

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::query()->firstOrCreate(
                [
                    'name' => $permissionName,
                    'guard_name' => self::GUARD,
                ],
            );

            $permissions[$permissionName] = $permission;
        }

        return $permissions;
    }

    /**
     * @param array<string, Permission> $permissions
     */
    private function createRoles(array $permissions): void
    {
        $admin = Role::query()->firstOrCreate(
            [
                'name' => 'admin',
                'guard_name' => self::GUARD,
            ],
        );

        $doctor = Role::query()->firstOrCreate(
            [
                'name' => 'doctor',
                'guard_name' => self::GUARD,
            ],
        );

        $caregiver = Role::query()->firstOrCreate(
            [
                'name' => 'caregiver',
                'guard_name' => self::GUARD,
            ],
        );

        $patient = Role::query()->firstOrCreate(
            [
                'name' => 'patient',
                'guard_name' => self::GUARD,
            ],
        );

        /*
         * Admin
         *
         * تمام Permissionهای سیستم را دارد.
         */
        $admin->syncPermissions(
            array_values($permissions),
        );

        /*
         * Doctor
         */
        $doctor->syncPermissions([
            $permissions['patients.view'],
            $permissions['patients.update'],

            $permissions['doctors.view'],

            $permissions['caregivers.view'],

            $permissions['health-records.view'],
            $permissions['health-records.create'],
            $permissions['health-records.update'],

            $permissions['medications.view'],
            $permissions['medications.create'],
            $permissions['medications.update'],

            $permissions['reminders.view'],
            $permissions['reminders.create'],
            $permissions['reminders.update'],

            $permissions['alerts.view'],
            $permissions['alerts.update'],

            $permissions['appointments.view'],
            $permissions['appointments.create'],
            $permissions['appointments.update'],

            $permissions['visits.view'],
            $permissions['visits.create'],
            $permissions['visits.update'],

            $permissions['reports.view'],
            $permissions['reports.create'],
        ]);

        /*
         * Caregiver
         */
        $caregiver->syncPermissions([
            $permissions['patients.view'],

            $permissions['health-records.view'],
            $permissions['health-records.create'],

            $permissions['medications.view'],

            $permissions['reminders.view'],
            $permissions['reminders.update'],

            $permissions['alerts.view'],

            $permissions['appointments.view'],

            $permissions['visits.view'],

            $permissions['reports.view'],
        ]);

        /*
         * Patient
         */
        $patient->syncPermissions([
            $permissions['patients.view'],

            $permissions['health-records.view'],
            $permissions['health-records.create'],

            $permissions['medications.view'],

            $permissions['reminders.view'],
            $permissions['reminders.update'],

            $permissions['alerts.view'],

            $permissions['appointments.view'],

            $permissions['visits.view'],

            $permissions['reports.view'],
        ]);
    }
}
