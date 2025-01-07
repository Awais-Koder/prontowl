<?php

namespace App\Filament\Resources\KycRequestResource\Pages;

use App\Filament\Resources\KycRequestResource;
use App\Models\Level;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKycRequest extends EditRecord
{
    protected static string $resource = KycRequestResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['status'] == 'approved'){
            $record = Level::where('user_id', $data['user_id'])->firstOrCreate();
            $record->level = $data['reuest_for_level'];
            $record->save();
        }
        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
