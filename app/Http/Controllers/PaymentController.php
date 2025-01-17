<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpayKey;
    private $razorpaySecret;

    public function __construct()
    {
        $this->razorpayKey = env('RAZORPAY_KEY');
        $this->razorpaySecret = env('RAZORPAY_SECRET');
    }

    public function showPaymentPage()
    {
        return view('payment');
    }

    public function createOrder(Request $request)
    {
        $api = new Api($this->razorpayKey, $this->razorpaySecret);

        $order = $api->order->create([
            'receipt' => 'order_rcptid_11',
            'amount' => $request->amount * 100, // amount in paise
            'currency' => 'INR'
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'razorpay_key' => $this->razorpayKey,
            'amount' => $request->amount * 100
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $api = new Api($this->razorpayKey, $this->razorpaySecret);

        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            return response()->json(['success' => true, 'message' => 'Payment verified successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
