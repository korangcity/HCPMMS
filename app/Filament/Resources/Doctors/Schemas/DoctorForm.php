<?php

declare(strict_types=1);

namespace App\Filament\Resources\Doctors\Schemas;

use App\Enums\AcademicDegree;
use App\Enums\DoctorSpecialty;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('کاربر')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('doctor'),
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('medical_license_number')
                    ->label('شماره نظام پزشکی')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                Select::make('specialty')
                    ->label('تخصص')
                    ->options(
                        collect(DoctorSpecialty::cases())
                            ->mapWithKeys(
                                fn (DoctorSpecialty $specialty): array => [
                                    $specialty->value => $specialty->label(),
                                ],
                            )
                            ->all()
                    )
                    ->searchable()
                    ->required(),

                Select::make('academic_degree')
                    ->label('مدرک تحصیلی')
                    ->options(
                        collect(AcademicDegree::cases())
                            ->mapWithKeys(
                                fn (AcademicDegree $degree): array => [
                                    $degree->value => $degree->label(),
                                ],
                            )
                            ->all()
                    )
                    ->required(),

                TextInput::make('years_of_experience')
                    ->label('سابقه کاری')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(70)
                    ->suffix('سال')
                    ->required(),

                Textarea::make('bio')
                    ->label('معرفی پزشک')
                    ->rows(4)
                    ->maxLength(2000)
                    ->columnSpanFull(),
            ]);
    }
}
