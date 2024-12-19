<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function payment()
{
    Stripe::setApiKey(config('services.stripe.secret'));
        // Create a charge
        $charge =  \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // You can add other payment methods if needed
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Judgify Order Ref No',
                        'description' => '$request_data["item_name"] . " : " . $request_data["order_ref_no"]',
                    ],
                    'unit_amount' => 900 * 100, // Amount in cents
                ],
                'quantity' => 1, // Quantity of the item
            ]],
            'mode' => 'payment', // For one-time payments. Use 'subscription' for recurring
            'metadata' => [
                'order_id' =>'123554',
                'event_id' => '3333',
            ],
            'success_url' => route('payment.success'),
            // 'cancel_url' => url('/checkout/cancel'),
        ]);
                //dd($charge);exit;
        // Save payment data to the database.
        // Payment::create([
            //     'transaction_id' => $charge->id,
            //     'amount' => $charge->amount / 100, // Convert back to dollars
            //     'currency' => $charge->currency,
            //     'status' => $charge->status,
            //     'customer_email' => $charge->receipt_email,
        // ]);
    return view('payment.payment',compact(
       'charge'       
    ));
}

public function processPayment(Request $request)
{
        Stripe::setApiKey(config('services.stripe.secret'));
        // Create a charge
        $charge =  \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // You can add other payment methods if needed
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Judgify Order Ref No',
                        'description' => '$request_data["item_name"] . " : " . $request_data["order_ref_no"]',
                    ],
                    'unit_amount' => 900 * 100, // Amount in cents
                ],
                'quantity' => 1, // Quantity of the item
            ]],
            'mode' => 'payment', // For one-time payments. Use 'subscription' for recurring
            'metadata' => [
                'order_id' =>'123554',
                'event_id' => '3333',
            ],
            'success_url' => route('payment.success'),
            // 'cancel_url' => url('/checkout/cancel'),
        ]);
                //dd($charge);exit;
        // Save payment data to the database.
        // Payment::create([
            //     'transaction_id' => $charge->id,
            //     'amount' => $charge->amount / 100, // Convert back to dollars
            //     'currency' => $charge->currency,
            //     'status' => $charge->status,
            //     'customer_email' => $charge->receipt_email,
        // ]);
         return redirect($charge->url);
        //return response()->json(['id' => $charge->id]);

}

public function success(){
    Log::info('payment success');
    return view('payment.payment-success');
}

public function cancel(){
    return view('payment.payment-failure');
}

public function handleWebhook(Request $request)
{
    //Log::info('Stripe Webhook Received:', $request->all());
    // Retrieve the Stripe event payload
    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');

    // Verify the webhook signature
    // $endpointSecret = config('services.stripe.webhook_secret');
    $endpointSecret = 'whsec_1487db3c38241e12f5aa644a9211be5882e7c5258a0d3774f83b6eecc019a680';

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $sigHeader,
            $endpointSecret
        );
    } catch (\UnexpectedValueException $e) {
        // Invalid payload
        return response('Invalid payload', 400);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        return response('Invalid signature', 400);
    }
    Log::info($event->type);
    if($event->type == 'checkout.session.completed')
    {
        $response = array();
        $response["order_ref_no"] = $event->data->object->metadata->order_id;
        $response["PayRef"]  = $event->data->object->id;
        $response["event_id"]  = $event->data->object->metadata->event_id;
        $response["amount"]  = $event->data->object->amount_total/100;
        $response["currency"]  = $event->data->object->currency;
        $response["type"]  = $event["type"];
        Log::info($response);
       
    }else{
        echo  "Invalid Stripe checkout Webhook.";   
        die(); 
       
    }
    return response('Webhook handled', 200);
}

public function testJson()
{
    return '{
        "object": {
          "id": "pi_3QTzSp4UDG8sQBwz0kikhzZM",
          "object": "payment_intent",
          "amount": 900,
          "amount_capturable": 0,
          "amount_details": {
            "tip": {}
          },
          "amount_received": 900,
          "application": null,
          "application_fee_amount": null,
          "automatic_payment_methods": null,
          "canceled_at": null,
          "cancellation_reason": null,
          "capture_method": "automatic_async",
          "client_secret": "pi_3QTzSp4UDG8sQBwz0kikhzZM_secret_9BJlMFk3Qa9btBuyuqkV1VynE",
          "confirmation_method": "automatic",
          "created": 1733721899,
          "currency": "usd",
          "customer": null,
          "description": null,
          "invoice": null,
          "last_payment_error": null,
          "latest_charge": "ch_3QTzSp4UDG8sQBwz08xCrZLB",
          "livemode": false,
          "metadata": {},
          "next_action": null,
          "on_behalf_of": null,
          "payment_method": "pm_1QTzSo4UDG8sQBwzDwjgQOv4",
          "payment_method_configuration_details": null,
          "payment_method_options": {
            "card": {
              "installments": null,
              "mandate_options": null,
              "network": null,
              "request_three_d_secure": "automatic"
            }
          },
          "payment_method_types": [
            "card"
          ],
          "processing": null,
          "receipt_email": null,
          "review": null,
          "setup_future_usage": null,
          "shipping": null,
          "source": null,
          "statement_descriptor": null,
          "statement_descriptor_suffix": null,
          "status": "succeeded",
          "transfer_data": null,
          "transfer_group": null
        },
        "previous_attributes": null
      }
}';
}
}