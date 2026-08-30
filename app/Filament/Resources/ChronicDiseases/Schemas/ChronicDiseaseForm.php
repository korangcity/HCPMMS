<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChronicDiseases\Schemas;

use App\Enums\ChronicDiseaseStatus;
use App\Filament\Forms\Components\PersianDateTimePicker;
use App\Models\Patient;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ChronicDiseaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
//                Select::make('patient_id')
//                    ->label('بیمار')
//                    ->relationship(
//                        name: 'patient',
//                        titleAttribute: 'id',
//                    )
//                    ->getOptionLabelFromRecordUsing(
//                        fn (Patient $patient): string => $patient
//                            ->user
//                            ?->name ?? 'بدون نام',
//                    )
//                    ->searchable([
//                        'user.name',
//                    ])
//                    ->preload()
//                    ->required(),

                TextInput::make('name')
                    ->label('بیماری')
                    ->required()
                    ->maxLength(255),

//                PersianDateTimePicker::make('diagnosed_at')
//                    ->label('تاریخ تشخیص')
//                    ->required(),

                Select::make('status')
                    ->label('وضعیت')
                    ->options(
                        ChronicDiseaseStatus::options(),
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
