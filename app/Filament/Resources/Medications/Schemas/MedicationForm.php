<?php

declare(strict_types=1);

namespace App\Filament\Resources\Medications\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class MedicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('مشخصات دارو')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('نام دارو')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('generic_name')
                            ->label('نام ژنریک')
                            ->maxLength(255),

                        TextInput::make('dosage_form')
                            ->label('شکل دارویی')
                            ->placeholder('Tablet / Capsule / Syrup'),

                        TextInput::make('strength')
                            ->label('دوز')
                            ->placeholder('500 mg'),

                        TextInput::make('manufacturer')
                            ->label('تولیدکننده'),

                        TextInput::make('code')
                            ->label('کد دارو')
                            ->unique(ignoreRecord: true),
                    ]),
                ]),

            Section::make('توضیحات')
                ->schema([
                    Textarea::make('description')
                        ->label('توضیحات')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
