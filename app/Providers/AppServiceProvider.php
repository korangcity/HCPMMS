<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Caregiver;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSign;
use App\Models\HealthReport;
use App\Models\HealthRecord;
use App\Models\DailyNote;
use App\Policies\CaregiverPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\PatientPolicy;
use App\Policies\UserPolicy;
use App\Policies\VitalSignPolicy;
use App\Policies\HealthReportPolicy;
use App\Policies\HealthRecordPolicy;
use App\Policies\DailyNotePolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        /*
         * User & Role Management
         */
        Gate::policy(
            User::class,
            UserPolicy::class,
        );

        /*
         * Patient Management
         */
        Gate::policy(
            Patient::class,
            PatientPolicy::class,
        );

        /*
         * Doctor Management
         */
        Gate::policy(
            Doctor::class,
            DoctorPolicy::class,
        );

        /*
         * Caregiver Management
         */
        Gate::policy(
            Caregiver::class,
            CaregiverPolicy::class,
        );

        /*
         * Health Monitoring
         */
        Gate::policy(
            VitalSign::class,
            VitalSignPolicy::class,
        );

        Gate::policy(
            HealthRecord::class,
            HealthRecordPolicy::class,
        );

        Gate::policy(
            DailyNote::class,
            DailyNotePolicy::class,
        );

        Gate::policy(
            HealthReport::class,
            HealthReportPolicy::class,
        );

        /*
         * Administrators bypass all authorization checks.
         */
        Gate::before(
            static function (
                User $user,
                string $ability,
            ): ?bool {
                return $user->hasRole('admin')
                    ? true
                    : null;
            },
        );
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn (): ?Password => app()->isProduction()
                ? Password::min(12)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : null,
        );
    }
}
