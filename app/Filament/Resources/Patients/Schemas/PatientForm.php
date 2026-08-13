<?php

declare(strict_types=1);

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

final class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات پایه')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('first_name')
                                ->label('نام')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('last_name')
                                ->label('نام خانوادگی')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('national_code')
                                ->label('کد ملی')
                                ->unique(ignoreRecord: true)
                                ->maxLength(10),

                            DatePicker::make('birth_date')
                                ->label('تاریخ تولد')
                                ->native(false),

                            Select::make('gender')
                                ->label('جنسیت')
                                ->options([
                                    'male' => 'مرد',
                                    'female' => 'زن',
                                    'other' => 'سایر',
                                ]),

                            TextInput::make('phone')
                                ->label('شماره تماس')
                                ->tel()
                                ->maxLength(20),
                        ]),
                ])
                ->columns(1),

            Section::make('اطلاعات پزشکی')
                ->schema([
                    Textarea::make('medical_history')
                        ->label('سابقه پزشکی')
                        ->rows(5)
                        ->columnSpanFull(),

                    Textarea::make('allergies')
                        ->label('حساسیت‌ها')
                        ->rows(4)
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->label('یادداشت')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
