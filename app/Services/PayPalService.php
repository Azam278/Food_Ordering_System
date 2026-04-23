<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalService
{
    private $client;
    private $environment;

    public function __construct()
    {
        $this->environment = env('PAYPAL_MODE') === 'sandbox' 
            ? new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
            : new ProductionEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
        
        $this->client = new PayPalHttpClient($this->environment);
    }

    public function createOrder($amount, $orderId, $description)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $orderId,
                'description' => $description,
                'amount' => [
                    'currency_code' => 'MYR',
                    'value' => number_format($amount, 2, '.', '')
                ]
            ]],
            'application_context' => [
                'cancel_url' => route('payment.error'),
                'return_url' => route('payment.success'),
                'brand_name' => env('APP_NAME', 'Food Ordering System'),
                'locale' => 'en-MY',
                'landing_page' => 'BILLING',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW'
            ]
        ];

        try {
            $response = $this->client->execute($request);
            
            // Store order info in session
            session([
                'payment_order_id' => $orderId,
                'payment_amount' => number_format($amount, 2, '.', ''),
                'paypal_order_id' => $response->result->id
            ]);

            return $response;
        } catch (\Exception $e) {
            throw new \Exception('PayPal order creation failed: ' . $e->getMessage());
        }
    }

    public function captureOrder($paypalOrderId)
    {
        $request = new OrdersCaptureRequest($paypalOrderId);

        try {
            $response = $this->client->execute($request);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('PayPal payment capture failed: ' . $e->getMessage());
        }
    }

    public function getApprovalLink($response)
    {
        foreach ($response->result->links as $link) {
            if ($link->rel === 'approve') {
                return $link->href;
            }
        }
        throw new \Exception('Approval link not found in PayPal response');
    }
}
