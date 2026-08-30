<?php

namespace App\Filament\Resources\ChronicDiseases\Pages;

use App\Filament\Resources\ChronicDiseases\ChronicDiseaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChronicDiseases extends ListRecords
{
    protected static string $resource = ChronicDiseaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
