<?php

declare(strict_types=1);

namespace App\Filament\Resources\Visits\Schemas;

use App\Filament\Forms\Components\PersianDateTimePicker;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

final class VisitForm
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
                        fn (
                            Patient $patient,
                        ): string => $patient
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
                        fn (
                            Doctor $doctor,
                        ): string => $doctor
                            ->user?->name
                            ?? "پزشک #{$doctor->id}",
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('appointment_id')
                    ->label('نوبت مرتبط')
                    ->relationship(
                        name: 'appointment',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (
                            Appointment $appointment,
                        ): string => sprintf(
                            '%s - %s',
                            $appointment->patient
                                ?->user?->name
                            ?? "بیمار #{$appointment->patient_id}",
                            $appointment->scheduled_at
                                ?->format('Y/m/d H:i')
                            ?? 'بدون تاریخ',
                        ),
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder(
                        'در صورت وجود نوبت انتخاب کنید',
                    ),

                PersianDateTimePicker::make('visited_at')
                    ->label('تاریخ و ساعت ویزیت')
                    ->required(),

                Textarea::make('doctor_notes')
                    ->label('یادداشت پزشک')
                    ->rows(5)
                    ->maxLength(10000)
                    ->columnSpanFull(),

                Textarea::make('diagnosis')
                    ->label('تشخیص')
                    ->rows(5)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('treatment_plan')
                    ->label('برنامه درمانی')
                    ->rows(5)
                    ->maxLength(10000)
                    ->columnSpanFull(),

                Textarea::make('follow_up')
                    ->label('پیگیری')
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ]);
    }
}
