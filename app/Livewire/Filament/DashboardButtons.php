<?php

namespace App\Livewire\Filament;

use App\Models\Deposit;
use App\Services\StripePaymentService;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Session;

class DashboardButtons extends Component
{
    #[Rule('required|numeric|min:1')]
    public $amount = '';
    public StripePaymentService $stripePaymentService;


    public function makePayment(StripePaymentService $stripePaymentService)
    {
        $this->validate();
        // Ensure the service is properly initialized before use
        if ($stripePaymentService) {
            $session = $stripePaymentService->createCheckoutSession(
                $this->amount * 100,
                'usd',
                'Deposit',
                'deposit.success'
            );
            Session::put('deposit_amount', $this->amount);
            return redirect($session->url);
        }
    }

    public function render()
    {
        return view('livewire.filament.dashboard-buttons');
    }
}
