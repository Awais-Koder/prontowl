<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use App\Models\Donation;
class HomeController extends Controller
{
    public function index($purpose, $id)
    {
        session()->put('campaign_data', base64_decode($id));
        return redirect()->route('filament.admin.resources.donations.create');
    }
    public function paymentSuccess()
    {
        $data = Session::get('donation_user_data');
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
        Donation::create($data);
        Notification::make()
            ->title('Payment Successful')
            ->body('Thank you for your generous donation!')
            ->success()
            ->send();
        return redirect()->back();
    }
}
