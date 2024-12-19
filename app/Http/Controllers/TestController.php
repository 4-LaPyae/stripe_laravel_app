<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class TestController extends Controller
{
    public function index()
    {
        return view('payment.test.payment');
    }
 
    public function pay(Request $request)
    {

        // Replace with your secret key, found in your Stripe dashboard
        Stripe::setApiKey(config('services.stripe.secret'));

        function calculateOrderAmount(array $items): int {
            return 499;
        }

        header('Content-Type: application/json');

        try {

            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            $paymentIntent = PaymentIntent::create([
                'amount' => calculateOrderAmount($jsonObj->items),
                'currency' => 'gbp', // Replace with your country's primary currency
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                // Remove if you don't want to send automatic email receipts after successful payment
                "receipt_email" => $request->email 
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }
}
