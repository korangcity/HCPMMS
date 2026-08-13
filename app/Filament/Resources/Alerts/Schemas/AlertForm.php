<?php

declare(strict_types=1);

namespace App\Filament\Resources\Alerts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class AlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات هشدار')
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

                        Select::make('type')
                            ->label('نوع هشدار')
                            ->options([
                                'blood_pressure' => 'فشار خون',
                                'blood_sugar' => 'قند خون',
                                'heart_rate' => 'ضربان قلب',
                                'oxygen' => 'اکسیژن خون',
                                'weight' => 'وزن',
                                'other' => 'سایر',
                            ])
                            ->required(),

                        Select::make('severity')
                            ->label('اولویت')
                            ->options([
                                'low' => 'کم',
                                'medium' => 'متوسط',
                                'high' => 'زیاد',
                                'critical' => 'بحرانی',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'open' => 'باز',
                                'acknowledged' => 'مشاهده شده',
                                'resolved' => 'برطرف شده',
                            ])
                            ->default('open')
                            ->required(),

                        TextInput::make('value')
                            ->label('مقدار ثبت‌شده')
                            ->numeric(),

                        TextInput::make('threshold')
                            ->label('حد آستانه')
                            ->numeric(),
                    ]),
                ]),

            Section::make('پیام هشدار')
                ->schema([
                    Textarea::make('message')
                        ->label('پیام')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
