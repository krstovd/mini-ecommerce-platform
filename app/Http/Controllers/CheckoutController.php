<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('checkout.index');
    }

    public function process(Request $request, CheckoutService $checkoutService)
    {
        try {
            $checkoutService->checkout(auth()->user(), $request->payment_method);

            return redirect('/')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
