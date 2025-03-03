<?php

namespace App\Http\Controllers;

use App\Jobs\SendImagesToUserJob;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     */
    public function paymentAction(Request $request)
    {
        $cart = Cart::with(["event_price.price", "event"])->where("token", $request->cookie("token"))->get()->groupBy("event_id");

        $total_price = 0;
        $count = 0;

        foreach($cart as $item){
            foreach($item as $i) {
                $total_price += (int)$i->event_price->price->price;
            }
        }
        try {

            return auth()->user()->checkoutCharge(
                $total_price*100, "", $count, [
                    "success_url" => route("frontend.payment.process.stripe.success"),
                    "cancel_url" => route("frontend.payment.process.stripe.error")
                ]
            );

        } catch (IncompletePayment $incompletePayment) {
            dd($incompletePayment);
            return redirect()->route();
        }
        dd($charge);

        return view("frontend.payment.process.stripe.process", compact( "charge"));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     */
    public function success(Request $request)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     */
    public function error(Request $request)
    {
    }
}
