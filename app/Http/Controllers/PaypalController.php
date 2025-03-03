<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\OrderPrice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal;

class PaypalController extends Controller
{

    public $provider;

    public function __construct()
    {
        $this->provider = new PayPal();
        $this->provider->setCurrency("GBP");

        $config = [
            'mode'    => 'live',
            'sandbox' => [
                'client_id'         => 'AdeuTa0wbjAwouEgDY2I_t52-39KsjekaqRUkyGESlVUfu8BQBF_2FtlW_RFZsVNWh4E2X0h57kiTU2i',
                'client_secret'     => 'EMPl9zQ7Zl3h--BsqujVDEoYnkclWh9F7fiCs9ieyWSlUHuhWqbM4_UdD6-YqspE3v-qFxUExTOyJZKp',
                'app_id'            => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'         => 'AVCRRJ9SnfMziAF4qzopGBj8QVH2l-dKMrZWZIW9iJYpZGZhvdi-D1HfQIIGVKCPIZdmR38ufrjMRtZe',
                'client_secret'     => 'EP1f-18y21gd-UjLlOi2PD-eqk04O5uyaqJFX5DhQr9c5obnZhyF2gK2aMG0IjtMkAad7mjpWvOE314e'
            ],
            'payment_action' => 'Order',
            'currency'       => 'GBP',
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];

        $this->provider->setApiCredentials($config);
        $this->provider->setAccessToken($this->provider->getAccessToken());
    }
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function payment(Request $request)
    {
        $cart = \App\Models\Cart::where("token", request()->cookie("token"))->get();

        $total_price = 0;

        foreach ($cart as $_cart) {
            $total_price += floatval($_cart->event_price->price->price);
        }

        $total_price = number_format($total_price,2);

        $order = $this->provider->createOrder([
            "intent"=> "CAPTURE",
            "purchase_units"=> [
                0 => [
                    "amount"=> [
                        "currency_code"=> "GBP",
                        "value"=> $total_price
                    ]
                ]
            ],
            'application_context' => [
                'cancel_url' => route("frontend.payment.process.paypal.cancel"),
                'return_url' => route("frontend.payment.process.paypal.success")
            ]
        ]);

        $href = $order["links"][1]["href"];

        return redirect($href);
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel()
    {
        return redirect()->route("frontend.cart.index")->with("status", "Your payment is canceled");
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */

    public function error()
    {
        dd(session("error"));
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @param Request $request
     * @param $user_id
     * @return void
     */
    public function success(Request $request)
    {
        $cart = \App\Models\Cart::where("token", request()->cookie("token"))->get();

        $order_number = $request->get("token");
//        $res = $this->provider->authorizePaymentOrder($order_number);
        $res = $this->provider->capturePaymentOrder($order_number);

        if($cart->count() <= 0){
            return redirect()->route("frontend.cart.index");
        }

        foreach ($cart as $_cart) {

            $order = Order::
            where("user_id",  $_cart->user_id)
                ->where("event_id", $_cart->event_id)
                ->where("status", 0)
                ->where("bib_number", $_cart->bib_number)
                ->first();

            if(!$order){
                $order = Order::create([
                    "order_number" => $order_number,
                    "user_id" => $_cart->user_id,
                    "event_id" => $_cart->event->id,
                    "event_title" => $_cart->event->title,

//                    "price" => $_cart->event_price->price->price,
//                    "price_type_name" => $_cart->event_price->price->text_type,
//                    "price_title" => $_cart->event_price->price->title,

                    "bucket_name" => $_cart->event->bucket_name,
                    "bib_number" => $_cart->bib_number,
                ]);
                OrderPrice::create([
                    "order_id" => $order->id,
                    "price" => $_cart->event_price->price->price,
                    "type" => $_cart->event_price->price->type,
                    "title" => $_cart->event_price->price->title
                ]);
            }

            OrderCreated::dispatch($order);
        }

        \App\Models\Cart::where("token", request()->cookie("token"))->delete();

        $user = $cart->first()->user()->first();

        $without_cart = true;

        return view("frontend.payment.process.paypal.success", compact("user", "without_cart"));
    }
}
