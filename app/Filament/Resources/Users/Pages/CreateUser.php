<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Services\UserService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(
        array $data,
    ): array
    {
        $data['email'] = null;

        return $data;
    }

    protected function handleRecordCreation(
        array $data,
    ): Model
    {
        $role = $data['role'];

        unset($data['role']);

        return app(UserService::class)->create(
            $data,
            $role,
        );
    }
}
