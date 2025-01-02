<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use App\Models\Donation;
class HomeController extends Controller
{
    public function index($id)
    {
        session()->put('campaign_data', base64_decode($id));
        return redirect()->route('filament.admin.resources.donations.create');
    }
    public function paymentSuccess()
    {
        if (Session::has('custom_form_data')) {
            $data = Session::get('donation_user_data');
            $data['campaign_id'] = Session::get('campaign_data');
            Donation::create($data);
        } else {
            $data = Session::get('donation_user_data');
            $campaignId = Session::get('campaign_data');
            $data['campaign_id'] = $campaignId;
            if ($data['donation_type'] !== 'custom') {
                $data['amount'] = $data['donation_type']; // Use the selected type as amount
            } else {
                $data['amount'] = $data['donation_amount']; // Use the custom amount
            }

            if ($data['tip_percentage'] === 'custom') {
                $data['tip_percentage'] = $data['tip_percentage_other']; // Use custom tip percentage
            }

            unset($data['donation_type'], $data['donation_amount'], $data['tip_percentage_other']);
            Donation::create($data);
            session()->flash('alert', [
                'type' => 'success', // or 'error', 'warning', etc.
                'message' => 'Thank you for your generous donation!',
            ]);
        }
        return view('payment-success');
    }
}
