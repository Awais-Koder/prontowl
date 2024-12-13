<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use App\Services\StripePaymentService;
class CreateDonation extends CreateRecord
{
    protected static string $resource = DonationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $campaignId = Session::get('campaign_data');
        $data['campaign_id'] = $campaignId;
        if (!empty($data['donation_type']) != 'custom') {
            $data['amount'] = !empty($data['donation_type']);
        } else {
            $data['amount'] = !empty($data['donation_amount']);
        }
        if (!empty($data['tip_percentage']) != 'custom') {
        } else {
            $data['tip_percentage'] = !empty($data['tip_percentage_other']);
        }
        unset($data['donation_type'], $data['donation_amount'], $data['tip_percentage_other']);
        return $data;
    }
}
