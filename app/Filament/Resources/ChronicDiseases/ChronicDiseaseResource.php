<?php

namespace App\Filament\Resources\ChronicDiseases;

use App\Filament\Resources\ChronicDiseases\Pages\CreateChronicDisease;
use App\Filament\Resources\ChronicDiseases\Pages\EditChronicDisease;
use App\Filament\Resources\ChronicDiseases\Pages\ListChronicDiseases;
use App\Filament\Resources\ChronicDiseases\Schemas\ChronicDiseaseForm;
use App\Filament\Resources\ChronicDiseases\Tables\ChronicDiseasesTable;
use App\Models\ChronicDisease;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChronicDiseaseResource extends Resource
{
    protected static ?string $model = ChronicDisease::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedHeart;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'بیماری‌های مزمن';

    protected static ?string $modelLabel = 'بیماری مزمن';

    protected static ?string $pluralModelLabel = 'بیماری‌های مزمن';

    public static function form(Schema $schema): Schema
    {
        return ChronicDiseaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChronicDiseasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChronicDiseases::route('/'),
            'create' => CreateChronicDisease::route('/create'),
            'edit' => EditChronicDisease::route('/{record}/edit'),
        ];
    }
}
