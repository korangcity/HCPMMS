<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;
use App\Models\Caregiver;
use App\Models\Doctor;
use App\Models\Patient;
use App\Policies\CaregiverPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\PatientPolicy;
use App\Policies\UserPolicy;

class AppServiceProvider extends ServiceProvider
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





        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(Doctor::class, DoctorPolicy::class);
        Gate::policy(Caregiver::class, CaregiverPolicy::class);

        Gate::before(
            static function (User $user, string $ability): ?bool {
                return $user->hasRole('admin') ? true : null;
            }
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

        Password::defaults(fn (): ?Password => app()->isProduction()
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
