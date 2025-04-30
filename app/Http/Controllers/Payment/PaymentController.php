<?php

namespace App\Http\Controllers\Payment;

use Omnipay\Omnipay;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Express');
        $this->gateway->setUsername(env('PAYPAL_USERNAME'));
        $this->gateway->setPassword(env('PAYPAL_PASSWORD'));
        $this->gateway->setSignature(env('PAYPAL_SIGNATURE'));
        $this->gateway->setTestMode(true);
    }

    public function pay($data)
    {
        try {
            $response = $this->gateway->purchase([
                'amount' => number_format($data['amount'], 2, '.', ''),
                'currency' => 'MYR',
                'returnUrl' => route('payment.success'),
                'cancelUrl' => route('payment.error'),
                'description' => $data['description'],
                'notifyUrl' => route('payment.notify'),
                'transactionId' => $data['orderId'],
                'custom' => $data['orderId'], // Add order ID in custom field
            ])->send();

            if ($response->isRedirect()) {
                session([
                    'payment_order_id' => $data['orderId'],
                    'payment_amount' => number_format($data['amount'], 2, '.', '')
                ]);
                
                return $response->getRedirectUrl();
            }

            throw new \Exception($response->getMessage());
        } catch (\Exception $e) {
            \Log::error('PayPal Error: ' . $e->getMessage());
            throw new \Exception('Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        if ($request->input('PayerID')) {
            $orderId = session('payment_order_id');
            
            try {
                $response = $this->gateway->completePurchase([
                    'transactionReference' => $request->input('token'),
                    'payerId' => $request->input('PayerID'),
                    'amount' => session('payment_amount'),
                    'currency' => 'MYR',
                ])->send();

                if ($response->isSuccessful()) {
                    $order = Order::findOrFail($orderId);
                    
                    // Create payment record
                    Payment::create([
                        'order_id' => $orderId,
                        'payment_method' => 'paypal',
                        'transaction_id' => $response->getTransactionReference(),
                        'amount' => $order->total,
                        'status' => 'completed',
                        'payment_details' => json_encode($response->getData())
                    ]);

                    // Update order status
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'completed'
                    ]);

                    session()->forget(['payment_order_id', 'payment_amount']);
                    // return redirect()->route('customer.order.confirmation', $orderId)
                    //     ->with('success', 'Payment successful!');\

                return redirect()->route('customer.order.confirmation', Crypt::encryptString($orderId))
                    ->with('success', 'Payment successful!');

                }

                return redirect()->route('customer.cart')
                    ->with('error', $response->getMessage());
            } catch (\Exception $e) {
                return redirect()->route('customer.cart')
                    ->with('error', $e->getMessage());
            }
        }

        return redirect()->route('customer.cart')
            ->with('error', 'Payment failed or cancelled.');
    }

    public function error()
    {
        return redirect()->route('customer.cart')
            ->with('error', 'Payment was cancelled.');
    }

    public function notify(Request $request)
    {
        // Verify IPN
        try {
            $response = $this->gateway->completePurchase([
                'transactionReference' => $request->input('txn_id'),
                'amount' => $request->input('mc_gross'),
                'currency' => $request->input('mc_currency'),
            ])->send();

            if ($response->isSuccessful()) {
                $orderId = $request->input('custom'); // You can pass order ID in custom field
                $order = Order::find($orderId);

                if ($order) {
                    // Update order status
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed'
                    ]);

                    // Log the IPN
                    \Log::info('PayPal IPN Received', $request->all());
                }
            }

            return response()->json(['status' => 'OK']);
        } catch (\Exception $e) {
            \Log::error('PayPal IPN Error: ' . $e->getMessage());
            return response()->json(['status' => 'ERROR'], 500);
        }
    }
}
