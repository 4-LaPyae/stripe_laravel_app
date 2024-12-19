<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;

class Stripe3Controller extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createPaymentIntent(Request $request)
    {
        $amount = $request->input('amount'); // Amount in dollars, e.g., 20 for $20.00
        $currency = 'usd';

        // Convert amount to cents for Stripe
        $amountInCents = $amount * 100;

        // Create a PaymentIntent
        $paymentIntent = $this->stripeService->createPaymentIntent($amountInCents, $currency);

        return response()->json([            
            'payment_intent_data' => $paymentIntent,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }
}
