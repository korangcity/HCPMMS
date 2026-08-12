<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Console\Command;

final class SeedUserRolesCommand extends Command
{
    protected $signature = 'users:seed-roles';

    protected $description = 'Create system roles and permissions';

    public function handle(): int
    {
        $this->call('db:seed', [
            '--class' => RolePermissionSeeder::class,
        ]);

        $this->info('Roles and permissions have been seeded.');

        return self::SUCCESS;
    }
}
