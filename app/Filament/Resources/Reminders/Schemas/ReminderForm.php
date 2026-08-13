<?php

declare(strict_types=1);

namespace App\Filament\Resources\Reminders\Schemas;

use App\Models\Patient;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ReminderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('یادآور')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('patient_id')
                            ->label('بیمار')
                            ->relationship(
                                name: 'patient',
                                titleAttribute: 'first_name',
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Patient $record): string =>
                                "{$record->first_name} {$record->last_name}"
                            )
                            ->searchable(['first_name', 'last_name'])
                            ->preload()
                            ->required(),

                        Select::make('type')
                            ->label('نوع یادآور')
                            ->options([
                                'medication' => 'دارو',
                                'appointment' => 'ویزیت',
                                'test' => 'آزمایش',
                            ])
                            ->required(),

                        TextInput::make('title')
                            ->label('عنوان')
                            ->required(),

                        DateTimePicker::make('remind_at')
                            ->label('زمان یادآوری')
                            ->native(false)
                            ->required(),

                        Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'pending' => 'در انتظار',
                                'completed' => 'انجام شده',
                                'cancelled' => 'لغو شده',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),
                ]),

            Section::make('توضیحات')
                ->schema([
                    Textarea::make('description')
                        ->label('توضیحات')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
