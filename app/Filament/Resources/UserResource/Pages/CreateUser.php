<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
    protected function afterCreate(): void
    {
        // Assign the role to the user after the user has been created
        $roleName = Role::find($this->data['role_id']);
        $this->record->assignRole($roleName->name);
    }
}
