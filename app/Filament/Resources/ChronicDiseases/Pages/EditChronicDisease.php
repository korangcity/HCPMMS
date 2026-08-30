<?php

namespace App\Filament\Resources\ChronicDiseases\Pages;

use App\Filament\Resources\ChronicDiseases\ChronicDiseaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChronicDisease extends EditRecord
{
    protected static string $resource = ChronicDiseaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
