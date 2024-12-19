<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Log the entire payload
        Log::info('Stripe Webhook Payload:', $request->all());

        // You can also verify the webhook signature here (recommended for production)
        $signatureHeader = $request->header('Stripe-Signature');
        
        try {
            $endpointSecret = config('services.stripe.webhook_secret'); // Ensure this is set in .env
            $payload = $request->getContent();

            // Verify the signature (optional but secure)
            \Stripe\Webhook::constructEvent(
                $payload,
                $signatureHeader,
                $endpointSecret
            );

            // Further processing based on event type
            $event = json_decode($payload, true);

            if ($event['type'] === 'payment_intent.succeeded') {
                Log::info('Payment Intent Succeeded:', $event['data']['object']);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 400);
        }
    }
}


// {
//     "id":"evt_1QUPlqGIW7elcKCuYUtrTUgW",
//     "object":"event",
//     "api_version":"2024-10-28.acacia",
//     "created":1733823022,
//     "data":
//         {"object":
//             {"id":"cs_test_a1tHwL02jEX2Fh0nRBRunjipBOuEyS8dYcLjySLu8iL8JoM6cmkAhpCTM5",
//                 "object":"checkout.session",
//                 "adaptive_pricing":{"enabled":false},
//                 "after_expiration":null,
//                 "allow_promotion_codes":null,
//                 "amount_subtotal":900,
//                 "amount_total":900,
//                 "automatic_tax":{"enabled":false,"liability":null,"status":null},
//                 "billing_address_collection":null,
//                 "cancel_url":"http://judgify.stripe.test.com/payment/cancel",
//                 "client_reference_id":null,"client_secret":null,"consent":null,
//                 "consent_collection":null,"created":1733823008,
//                 "currency":"usd","currency_conversion":null,
//                 "custom_fields":[],
//                 "custom_text":{"after_submit":null,"shipping_address":null,"submit":null,"terms_of_service_acceptance":null},
//                 "customer":null,"customer_creation":"if_required",
//                 "customer_details":{"address":{"city":null,"country":"MM","line1":null,"line2":null,"postal_code":null,"state":null},"email":"lapyae2022.gm@gmail.com","name":"payment card","phone":null,"tax_exempt":"none","tax_ids":[]},
//                 "customer_email":null,"expires_at":1733909408,"invoice":null,
//                 "invoice_creation":{"enabled":false,
//                 "invoice_data":{"account_tax_ids":null,"custom_fields":null,"description":null,"footer":null,"issuer":null,"metadata":[],"rendering_options":null}},"livemode":false,"locale":null,"metadata":{"description":"Payment for Sample Product - Order #12345","user_id":"67890","order_id":"12345"},"mode":"payment","payment_intent":"pi_3QUPlpGIW7elcKCu1nNML2bj","payment_link":null,"payment_method_collection":"if_required","payment_method_configuration_details":null,"payment_method_options":{"card":{"request_three_d_secure":"automatic"}},"payment_method_types":["card"],"payment_status":"paid","phone_number_collection":{"enabled":false},"recovered_from":null,"saved_payment_method_options":null,"setup_intent":null,"shipping_address_collection":null,"shipping_cost":null,"shipping_details":null,"shipping_options":[],"status":"complete","submit_type":null,"subscription":null,"success_url":"http://judgify.stripe.test.com/payment/success","total_details":{"amount_discount":0,"amount_shipping":0,"amount_tax":0},"ui_mode":"hosted","url":null}},"livemode":false,"pending_webhooks":4,"request":{"id":null,"idempotency_key":null},"type":"checkout.session.completed"} 