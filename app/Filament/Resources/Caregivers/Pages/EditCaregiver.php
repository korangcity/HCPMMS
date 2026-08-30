<?php

namespace App\Filament\Resources\Caregivers\Pages;

use App\Filament\Resources\Caregivers\CaregiverResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCaregiver extends EditRecord
{
    protected static string $resource = CaregiverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
