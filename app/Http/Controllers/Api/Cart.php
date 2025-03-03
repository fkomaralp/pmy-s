<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendImagesRequest;
use App\Models\Order;
use App\Models\OrderPrice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Cart extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $result = [
            "status" => false,
            "message" => "Token not found."
        ];

        if(cookie()->has("token")){
            $cart = \App\Models\Cart::where("token", cookie()->get("token"))->get();

            $result = [
                "status" => true,
                "data" => $cart,
                "count" => $cart->groupBy("event_id")->count()
            ];
        }


        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $cart = \App\Models\Cart::
        where("event_id", $request->get("event_id"))
        ->where("bib_number" , $request->get("bib_number"))
        ->where("token", $request->cookie("token"))
        ->first();

        if($cart){
            $cart->delete();
        }

        \App\Models\Cart::create([
            "event_id" => $request->get("event_id"),
            "price_id" => $request->get("price_id"),
            "bib_number" => $request->get("bib_number"),
            "token" => $request->cookie("token")
        ]);

        $cart = \App\Models\Cart::where("token", $request->cookie("token"))->get();

        $result = [
            "status" => true,
//            "redirect" => route(""),
            "message" => "Images added to you cart!",
            "count" => $cart->count()
        ];

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendImages(SendImagesRequest $request)
    {
        $user = User::where("email", $request->email)->first();
 
        $result = [
            "status" => true,
            "message" => "Your images will be sent to your email."
        ];

        if(!$user){

            $password = uniqid();
            $password_hashed = bcrypt($password);

            $user = User::create([
                "email" =>  $request->email,
                "name" => $request->name,
                "address" => $request->address,
                "postcode" => $request->postcode,
                "password" => $password_hashed,
            ]);

        }

        $cart = \App\Models\Cart::where("token", request()->cookie("token"))->get();

        $order = false;
        $require_to_reload = false;
        foreach ($cart as $_cart) {
            $_order = Order::
            where("user_id",  $user->id)
            ->where("event_id", $_cart->event_id)
            ->where("status", 0)
            ->where("bib_number", $_cart->bib_number)->get();

            if($_order->count() < 1){
                $order = Order::create([
                    "order_number" => $request->order_number,
                    "user_id" => $user->id,
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

            } else {
                $order = $_order->first();

//                $result["status"] = true;
//                $result["message"] = "This action is already in progress.";

            }

//            foreach($order->event->price_list as $price){
//                $require_to_reload = floatval($price->price->price) > 0;
//            }

        }

        if($order && !$require_to_reload){
            OrderCreated::dispatch($order);

            \App\Models\Cart::where("token", request()->cookie("token"))->delete();
        }

        if($require_to_reload){
            $result["status"] = false;
            $result["message"] = "Basket has been updated. The page is reloading. ";
            $result["reload"] = true;
        }


        return JsonResponse::fromJsonString(json_encode($result));
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
    public function destroy(int $id)
    {
        \App\Models\Cart::destroy($id);

        $result = [
            "status" => true,
            "message" => "Item is deleted from your cart."
        ];

        return JsonResponse::fromJsonString(json_encode($result));
    }
}
