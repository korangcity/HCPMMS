<?php

namespace App\Filament\Resources\Caregivers\Pages;

use App\Filament\Resources\Caregivers\CaregiverResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCaregivers extends ListRecords
{
    protected static string $resource = CaregiverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
