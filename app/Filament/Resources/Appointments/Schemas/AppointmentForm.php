<?php

declare(strict_types=1);

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\AppointmentStatus;
use App\Filament\Forms\Components\PersianDateTimePicker;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

final class AppointmentForm
{
    public static function configure(
        Schema $schema,
    ): Schema {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->label('بیمار')
                    ->relationship(
                        name: 'patient',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (Patient $patient): string => $patient
                            ->user?->name
                            ?? "بیمار #{$patient->id}",
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('doctor_id')
                    ->label('پزشک')
                    ->relationship(
                        name: 'doctor',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (Doctor $doctor): string => $doctor
                            ->user?->name
                            ?? "پزشک #{$doctor->id}",
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                PersianDateTimePicker::make('scheduled_at')
                    ->label('تاریخ و ساعت نوبت')
                    ->required()
                    ,

                Select::make('status')
                    ->label('وضعیت')
                    ->options(
                        collect(AppointmentStatus::cases())
                            ->mapWithKeys(
                                fn (
                                    AppointmentStatus $status,
                                ): array => [
                                    $status->value => $status->label(),
                                ],
                            )
                            ->all(),
                    )
                    ->default(
                        AppointmentStatus::Scheduled->value,
                    )
                    ->required()
                    ->native(false),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(4)
                    ->maxLength(2000)
                    ->columnSpanFull(),
            ]);
    }
}
