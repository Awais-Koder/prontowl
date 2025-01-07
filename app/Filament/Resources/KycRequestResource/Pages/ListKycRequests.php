<?php

namespace App\Filament\Resources\KycRequestResource\Pages;

use App\Filament\Resources\KycRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKycRequests extends ListRecords
{
    protected static string $resource = KycRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
