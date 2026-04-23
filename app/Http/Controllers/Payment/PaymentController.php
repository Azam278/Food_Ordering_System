<?php

namespace App\Http\Controllers\Payment;

use App\Services\PayPalService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    private $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function pay($data)
    {
        try {
            $response = $this->paypalService->createOrder(
                $data['amount'],
                $data['orderId'],
                $data['description']
            );

            return $this->paypalService->getApprovalLink($response);
        } catch (\Exception $e) {
            \Log::error('PayPal Error: ' . $e->getMessage());
            throw new \Exception('Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $paypalOrderId = $request->input('token');
        $orderId = session('payment_order_id');
        
        if (!$paypalOrderId || !$orderId) {
            return redirect()->route('customer.cart')
                ->with('error', 'Payment failed or cancelled.');
        }

        try {
            $response = $this->paypalService->captureOrder($paypalOrderId);

            if ($response->result->status === 'COMPLETED') {
                $order = Order::findOrFail($orderId);
                
                // Create payment record
                Payment::create([
                    'order_id' => $orderId,
                    'payment_method' => 'paypal',
                    'transaction_id' => $response->result->id,
                    'amount' => $order->total,
                    'status' => 'completed',
                    'payment_details' => json_encode($response->result)
                ]);

                // Update order status
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'completed'
                ]);

                session()->forget(['payment_order_id', 'payment_amount', 'paypal_order_id']);

                return redirect()->route('customer.order.confirmation', Crypt::encryptString($orderId))
                    ->with('success', 'Payment successful!');
            }

            return redirect()->route('customer.cart')
                ->with('error', 'Payment was not completed.');
        } catch (\Exception $e) {
            \Log::error('PayPal Capture Error: ' . $e->getMessage());
            return redirect()->route('customer.cart')
                ->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function error()
    {
        return redirect()->route('customer.cart')
            ->with('error', 'Payment was cancelled.');
    }

    public function notify(Request $request)
    {
        // For REST API, you'd implement webhook handling here
        \Log::info('PayPal Webhook Received', $request->all());
        return response()->json(['status' => 'OK']);
    }
}
