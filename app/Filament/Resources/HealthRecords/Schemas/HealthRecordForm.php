<?php

declare(strict_types=1);

namespace App\Filament\Resources\HealthRecords\Schemas;

use App\Enums\Medication;
use App\Filament\Forms\Components\PersianDateTimePicker;
use App\Models\ChronicDisease;
use App\Models\Patient;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class HealthRecordForm
{
    public static function configure(Schema $schema): Schema
    {
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

                Select::make('chronic_disease_id')
                    ->label('بیماری مزمن')
                    ->relationship(
                        name: 'chronicDisease',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder(
                        'در صورت ارتباط انتخاب کنید',
                    )
                    ->nullable(),

                Repeater::make('measurement_information')
                    ->label('اندازه‌گیری‌ها')
                    ->schema([
                        Select::make('type')
                            ->label('نوع اندازه‌گیری')
                            ->options([
                                'blood_pressure' => 'فشار خون',
                                'blood_glucose' => 'قند خون',
                                'weight' => 'وزن',
                                'temperature' => 'دمای بدن',
                                'heart_rate' => 'ضربان قلب',
                                'oxygen_saturation' => 'اشباع اکسیژن',
                            ])
                            ->required()
                            ->searchable()
                            ->native(false),

                        TextInput::make('value')
                            ->label('مقدار')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('مثلاً 120/80'),

                        TextInput::make('unit')
                            ->label('واحد')
                            ->required()
                            ->maxLength(20)
                            ->placeholder(
                                'مثلاً mmHg، mg/dL، bpm',
                            ),

                        PersianDateTimePicker::make('recorded_at')
                            ->label('زمان اندازه‌گیری')
                            ->required(),
                    ])
                    ->columns(4)
                    ->defaultItems(1)
                    ->addActionLabel(
                        'افزودن اندازه‌گیری',
                    )
                    ->reorderable()
                    ->collapsible()
                    ->cloneable()
                    ->columnSpanFull()
                    ->required(),

                Repeater::make('medication_information')
                    ->label('تجویز دارو')
                    ->schema([
                        Select::make('medication')
                            ->label('دارو')
                            ->options(
                                collect(Medication::cases())
                                    ->mapWithKeys(
                                        fn (
                                            Medication $medication,
                                        ): array => [
                                            $medication->value
                                            => $medication->label(),
                                        ],
                                    )
                                    ->all(),
                            )
                            ->searchable()
                            ->native(false)
                           ,

                        TextInput::make('dosage')
                            ->label('دوز')
                            ->maxLength(100)
                            ->placeholder(
                                'مثلاً 500 mg',
                            ),

                        TextInput::make(
                            'administration_method',
                        )
                            ->label('نحوه مصرف')
                            ->maxLength(100)
                            ->placeholder(
                                'مثلاً خوراکی',
                            ),

                        TextInput::make('frequency')
                            ->label('تعداد / دفعات')
                            ->maxLength(100)
                            ->placeholder(
                                'مثلاً روزی 2 بار',
                            ),

                        PersianDateTimePicker::make('start_date')
                            ->label('تاریخ شروع')
                            ,

                        PersianDateTimePicker::make('end_date')
                            ->label('تاریخ پایان')
                            ->nullable()
                            ->afterOrEqual('start_date'),
                    ])
                    ->columns(3)
                    ->defaultItems(0)
                    ->addActionLabel(
                        'افزودن دارو',
                    )
                    ->reorderable()
                    ->collapsible()
                    ->cloneable()
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(4)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                Select::make('recorded_by')
                    ->label('ثبت‌کننده')
                    ->relationship(
                        name: 'recorder',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role(
                            'doctor',
                        ),
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
                        fn ($record): string => sprintf(
                            '%s - %s',
                            $record->patient?->user?->name
                            ?? "بیمار #{$record->patient_id}",
                            $record->scheduled_at
                                ?->format('Y/m/d H:i')
                            ?? 'بدون تاریخ',
                        ),
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('در صورت وجود انتخاب کنید'),
                Select::make('visit_id')
                    ->label('ویزیت مرتبط')
                    ->relationship(
                        name: 'visit',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => sprintf(
                            '%s - %s',
                            $record->patient?->user?->name
                            ?? "بیمار #{$record->patient_id}",
                            $record->visited_at
                                ?->format('Y/m/d H:i')
                            ?? 'بدون تاریخ',
                        ),
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('در صورت وجود انتخاب کنید'),
            ]);
    }
}
