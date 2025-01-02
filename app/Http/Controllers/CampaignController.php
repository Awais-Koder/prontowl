<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Carbon\Carbon;
use App\Models\Donation;
use Illuminate\Http\Request;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Session;
class CampaignController extends Controller
{
    public $stripePaymentService;
    public function __construct()
    {
        $this->stripePaymentService = new StripePaymentService();
    }
    public function discover($category = null)
    {
        $query = Campaign::query();

        if (!empty($category)) {
            $query->where('purpose', 'like', '%' . $category);
        }
        $campaigns = $query->latest()->withCount('donations')->with('donations')->where('ending_date', '>', Carbon::now())->paginate(9);

        return view('campaign.discover-campaigns', ['campaigns' => $campaigns]);
    }

    public function show($id)
    {
        $campaign = Campaign::with('donations')->withCount('donations')->findOrFail(base64_decode($id));
        $donations = $campaign->donations()->latest()->paginate(10);
        return view('campaign.show-campaign', ['campaign' => $campaign, 'donations' => $donations]);
    }
    public function fetchDonors($id)
    {
        $donations = Donation::where('campaign_id', $id)->latest()->get()->skip(10);
        $count = $donations->count();
        return response()->json([
            'html' => view('custom-components.donors', compact('donations'))->render(),
        ]);
    }

    public function makeDonation($id)
    {
        $campaign = Campaign::with('donations')->withCount('donations')->findOrFail(base64_decode($id));
        return view('campaign.make-donation', compact('campaign'));
    }
    public function payDonation(Request $request, $id)
    {
        $request->validate([
            'opt_out_tip' => 'nullable|boolean',
        'tip_percentage' => 'required_if:opt_out_tip,false|numeric|min:0|max:100',
        ]);
        $campaignId = base64_decode($id);
        $data = $request->all();
        Session::put('donation_user_data', $data);
        Session::put('campaign_data', $campaignId);
        Session::put('custom_form_data', true);
        $session = $this->stripePaymentService->createCheckoutSession($data['amount'] * 100, 'usd' , 'Donation' , 'payment.success');
        return redirect($session->url);
    }

}
