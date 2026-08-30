<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Sex;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\autocomplete;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام و نام خانوادگی')
                    ->required()
                    ->maxLength(255),

                TextInput::make('mobile')
                    ->label('شماره موبایل')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->unique(
                        ignoreRecord: true,
                    ),

                TextInput::make('civilization_code')
                    ->label('کد ملی')
                    ->required()
                    ->maxLength(20)
                    ->unique(
                        ignoreRecord: true,
                    ),

                Select::make('sex')
                    ->label('جنسیت')
                    ->options(
                        collect(Sex::cases())
                            ->mapWithKeys(
                                fn (Sex $sex): array => [
                                    $sex->value => $sex->label(),
                                ],
                            )
                            ->all(),
                    )
                    ->required(),
                TextInput::make('age')
                    ->label('سن')
                    ->integer()
                    ->minValue(1)
                    ->maxValue(150)
                    ->required(),

                TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->nullable()
                    ->autocomplete('off')
                    ->default(null)
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                    ),

                TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(
                        fn (?string $state): bool => filled($state),
                    )
                    ->minLength(8)
                    ->maxLength(255),

                Select::make('role')
                    ->label('نقش')
                    ->options([
                        'admin' => 'مدیر سیستم',
                        'doctor' => 'پزشک',
                        'caregiver' => 'مراقب',
                        'patient' => 'بیمار',
                    ])
                    ->required()
                    ->afterStateHydrated(
                        function (
                            Select $component,
                            ?User $record,
                        ): void {
                            $component->state(
                                $record?->getRoleNames()->first(),
                            );
                        },
                    ),
            ]);
    }
}
