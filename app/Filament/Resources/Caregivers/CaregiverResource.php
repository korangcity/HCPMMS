<?php

namespace App\Filament\Resources\Caregivers;

use App\Filament\Resources\Caregivers\Pages\CreateCaregiver;
use App\Filament\Resources\Caregivers\Pages\EditCaregiver;
use App\Filament\Resources\Caregivers\Pages\ListCaregivers;
use App\Filament\Resources\Caregivers\Schemas\CaregiverForm;
use App\Filament\Resources\Caregivers\Tables\CaregiversTable;
use App\Models\Caregiver;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CaregiverResource extends Resource
{
    protected static ?string $model = Caregiver::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'مراقبین';

    protected static ?string $modelLabel = 'مراقب';

    protected static ?string $pluralModelLabel = 'مراقبین';

    protected static string|null|\UnitEnum $navigationGroup = 'مدیریت کاربران';

    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'caregiver';

    public static function form(Schema $schema): Schema
    {
        return CaregiverForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CaregiversTable::configure($table);
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
            'index' => ListCaregivers::route('/'),
            'create' => CreateCaregiver::route('/create'),
            'edit' => EditCaregiver::route('/{record}/edit'),
        ];
    }
}
