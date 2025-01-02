<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
class StripePaymentService
{
    public function __construct()
    {
       Stripe::setApiKey(config('services.stripe.stripe_secret_key'));
    }
    public function createCheckoutSession($amount, $currency = 'usd' , $name , $route)
    {
        return Session::create([
            'payment_method_types' => ['card'], // Can include other types like 'alipay'
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $name, // Name of your product
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route($route), // Define success page route
            'cancel_url' => route('payment.cancel'),   // Define cancel page route
        ]);
    }
}
