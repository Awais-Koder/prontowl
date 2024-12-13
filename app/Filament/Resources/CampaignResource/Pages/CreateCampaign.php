<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        if($data['used_in'] == 'other'){
            $data['used_in'] = $data['used_in_other'];
        }
        unset($data['used_in_other']);
        $data['gallary_images'] = json_encode($data['gallary_images']);
        // dd($data);
        return $data;
    }
}
