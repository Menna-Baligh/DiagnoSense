<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargeWalletRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Responses\ApiResponse;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class WalletController extends Controller
{
    public function index()
    {
        $currentDoctor = auth()->user()->doctor;
        $transactions = $currentDoctor->transactions()->get();

        return ApiResponse::success(message: 'Wallet transactions retrieved successfully', data: TransactionResource::collection($transactions), statusCode: 200);
    }

    public function store(ChargeWalletRequest $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'egp',
                    'product_data' => [
                        'name' => 'Wallet Charge',
                    ],
                    'unit_amount' => $request->balance * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'client_reference_id' => auth()->user()->doctor->id,
            'metadata' => [
                'doctor_id' => auth()->user()->doctor->id,
                'amount' => $request->balance,
            ],
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return response()->json([
            'success' => true,
            'checkout_url' => $session->url,
        ]);
    }
}
