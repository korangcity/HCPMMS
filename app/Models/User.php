<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Sex;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'mobile', 'sex', 'civilization_code', 'age',])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,HasRoles;


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sex' => Sex::class,
            'password' => 'hashed',
        ];
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function caregiver(): HasOne
    {
        return $this->hasOne(Caregiver::class);
    }

    public function recordedHealthRecords(): HasMany
    {
        return $this->hasMany(
            HealthRecord::class,
            'recorded_by',
        );
    }
}
