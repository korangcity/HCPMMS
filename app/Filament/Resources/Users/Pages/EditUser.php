<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use App\Services\UserService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    private ?string $role = null;

    protected function handleRecordUpdate(
        Model $record,
        array $data,
    ): Model {
        /** @var User $record */
        $role = $data['role'] ?? null;

        unset($data['role']);

        return app(UserService::class)->update(
            $record,
            $data,
            $role,
        );
    }
}
