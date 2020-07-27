<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    // added for security
    // allows only authenticated users to enter here
    public function __construct() {
        $this->middleware('auth');
    }

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
    public function store(CheckoutRequest $request)
    {
        // Serverside validation done using Checkout Request
        // Now you have all the user information to store the database
        // or pass the order information to Stripe dashboard like we did below
        // with the $contents variable

        // make the cart contents available as string format
        $contents = Cart::content()->map(function($item) {
            return $item->model->id.', '.$item->model->name.', '.$item->qty;
        })->values()->toJson();

        try {
            // Set your secret key. Remember to switch to your live secret key in production!
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            // use secret key here
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $intent = PaymentIntent::create([
                'amount' => Cart::total(),  // here we send Stripe total money as cents
                'currency' => 'EUR',
                'description'   => 'Order',
                'receipt_email' => $request->customerEmail,
                // Verify your integration in this guide by including this parameter
                'metadata' => [
                    'contents' => $contents,
                    'total_quantity' => Cart::count(),
                ],
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
