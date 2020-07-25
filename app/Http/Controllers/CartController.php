<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    // added for api security
    // allows only javascript applications with JWT to access api route
    public function __construct() {
        $this->middleware('auth');
    }

    public function addProduct(Request $request)
    {
        // validate the price
        $price = (int)str_replace(',', '', str_replace('.', '', $request->price));
        $message = Cart::add($request->id, $request->name, $request->quantity, $price)
            ->associate('App\Product');
        
        return [
            'success_message' => 'Product successfully added to your cart!',
            'cartCount' => Cart::count(),
        ];
    }

    // Custom methods
    public function checkIfCartEmpty() {
        return [
            'cartContent' => Cart::content(),
            'subTotal'  => formatPrice(Cart::subtotal()),
            'tax'   => formatPrice(Cart::tax()),
            'total' => formatPrice(Cart::total()),
            'cartCount' => Cart::count(),
        ];
    }

    public function getCartCount() {
        return [
            'cartCount' => Cart::count(),
        ];
    }

    public function removeCartItem(Request $request) {
        $cart_item_id = $request->cartItemId;
        Cart::remove($cart_item_id);
        
        return [
            'success_message' => 'Product has been removed',
        ];
    }
}
