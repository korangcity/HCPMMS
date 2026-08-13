<?php

declare(strict_types=1);

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات ویزیت')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('patient_id')
                            ->label('بیمار')
                            ->relationship(
                                name: 'patient',
                                titleAttribute: 'first_name',
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn ($record): string =>
                                "{$record->first_name} {$record->last_name}"
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('doctor_id')
                            ->label('پزشک')
                            ->relationship(
                                name: 'doctor',
                                titleAttribute: 'first_name',
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn ($record): string =>
                                "{$record->first_name} {$record->last_name}"
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        DateTimePicker::make('scheduled_at')
                            ->label('زمان ویزیت')
                            ->native(false)
                            ->required(),

                        Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'scheduled' => 'برنامه‌ریزی شده',
                                'completed' => 'انجام شده',
                                'cancelled' => 'لغو شده',
                                'no_show' => 'عدم مراجعه',
                            ])
                            ->default('scheduled')
                            ->required(),

                        TextInput::make('type')
                            ->label('نوع ویزیت')
                            ->default('routine'),

                        TextInput::make('location')
                            ->label('محل ویزیت'),
                    ]),
                ]),

            Section::make('توضیحات')
                ->schema([
                    Textarea::make('notes')
                        ->label('یادداشت')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
