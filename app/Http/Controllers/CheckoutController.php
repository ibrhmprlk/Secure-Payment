<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

  public function process(Request $request)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:1|max:100000',
        'payment_method_id' => 'required|string',
        'card_holder' => 'required|string|min:2|max:100',
    ]);

    try {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Daha güvenli amount conversion
        $amountInCents = (int) bcmul($validated['amount'], '100', 0);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'payment_method' => $validated['payment_method_id'],
            'confirm' => true,
            'description' => 'Payment by ' . $validated['card_holder'],
            'metadata' => [
                'card_holder' => $validated['card_holder'],
                'amount_usd' => $validated['amount']
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);

        // ⭐ EKLENMELİ: Status kontrolü
        if ($paymentIntent->status !== 'succeeded') {
            return response()->json([
                'success' => false,
                'error' => 'Payment requires additional action: ' . $paymentIntent->status
            ], 400);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('checkout.success'),
            'payment_intent_id' => $paymentIntent->id,
        ]);

    } catch (\Stripe\Exception\CardException $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getError()->message
        ], 400);
        
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        // ⭐ EKLENMELİ: Invalid request handling
        return response()->json([
            'success' => false,
            'error' => 'Invalid payment request'
        ], 400);
        
    } catch (\Exception $e) {
        Log::error('Payment failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString() // ⭐ Detaylı log
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Payment processing failed. Please try again.'
        ], 500);
    }
}

    public function success()
    {
        return view('success');
    }

    public function error()
    {
        return view('error');
    }
}