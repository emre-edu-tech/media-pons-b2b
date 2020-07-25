<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // do serverside validation

        // get stripe info
        try {
            // Set your secret key. Remember to switch to your live secret key in production!
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            // use secret key here
            Stripe::setApiKey('sk_test_2PftfZwkYHy4T1Xz9fZZeilN');

            $intent = PaymentIntent::create([
                'amount' => Cart::total(),
                'currency' => 'EUR',
                'description'   => 'Order',
                'receipt_email' => $request->customerEmail,
                // Verify your integration in this guide by including this parameter
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);

            // Transaction successful save order information to database
            // Possible orders table successful entry
            return [
                'success'  => true,
                'client_secret' => $intent->client_secret,
            ];
        } catch (Exception $e) {
            // Transaction failed. Store failed transaction to your database
            // Possible orders table failed entry
            return [
                'success'   => false,
                'error_message' => $e.getMessage(),
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
