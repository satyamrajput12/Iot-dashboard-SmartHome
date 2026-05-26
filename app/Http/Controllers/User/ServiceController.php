<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index()
    {
        // Get user's past service requests
        $requests = auth()->user()->serviceRequests()->orderBy('created_at', 'desc')->get();
        return view('user.services.index', compact('requests'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'pincode'        => 'required|string|max:20'
        ]);

        $amount = 2.00; // Rs 2

        $fullAddress = sprintf(
            "%s, %s, %s, %s - %s",
            $request->address_line_1,
            $request->address_line_2 ?? '',
            $request->city,
            $request->state,
            $request->pincode
        );
        $fullAddress = str_replace(', ,', ',', $fullAddress); // Cleanup if line 2 is empty

        $serviceRequest = ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_name' => 'Tech Team Booking',
            'address' => $fullAddress,
            'amount' => $amount,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $orderData = [
                'receipt'         => 'rcptid_'.$serviceRequest->id,
                'amount'          => $amount * 100, // in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];

            $razorpayOrder = $api->order->create($orderData);
            
            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $amount * 100,
                'request_id' => $serviceRequest->id,
                'key' => env('RAZORPAY_KEY')
            ]);
        } catch (\Exception $e) {
            \Log::error('Razorpay Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Razorpay Error: ' . $e->getMessage()
            ]);
        }
    }

    public function verifyPayment(Request $request)
    {
        $signatureStatus = false;
        
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try {
            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );
            $api->utility->verifyPaymentSignature($attributes);
            $signatureStatus = true;
        } catch (\Exception $e) {
            $signatureStatus = false;
        }

        if ($signatureStatus) {
            $serviceRequest = ServiceRequest::findOrFail($request->request_id);
            $serviceRequest->update([
                'payment_status' => 'paid',
                'razorpay_payment_id' => $request->razorpay_payment_id
            ]);

            return redirect()->route('user.services.index')->with('success', 'Payment successful! Tech Team booked. Waiting for admin approval.');
        } else {
            return redirect()->route('user.services.index')->with('error', 'Payment verification failed.');
        }
    }

    public function contactUs(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000'
        ]);

        ServiceRequest::create([
            'user_id' => auth()->id() ?? 1,
            'service_name' => 'Contact Inquiry',
            'address' => 'Email: ' . $request->email . "\nMessage: " . $request->message,
            'amount' => 0.00,
            'status' => 'pending',
            'payment_status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Your message has been sent to the admin successfully!');
    }
}
