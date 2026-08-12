<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.assign-roles',

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

            'patients.assign-doctor',
            'patients.assign-caregiver',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate(
                $permission,
                'web'
            );
        }

        $admin = Role::findOrCreate('admin', 'web');
        $doctor = Role::findOrCreate('doctor', 'web');
        $caregiver = Role::findOrCreate('caregiver', 'web');
        $patient = Role::findOrCreate('patient', 'web');

        $admin->syncPermissions($permissions);

        $doctor->syncPermissions([
            'users.view',

            'patients.view',
            'patients.update',

            'doctors.view',
            'doctors.update',
        ]);

        $caregiver->syncPermissions([
            'users.view',

            'patients.view',
            'patients.update',

            'caregivers.view',
            'caregivers.update',
        ]);

        $patient->syncPermissions([
            'users.view',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
