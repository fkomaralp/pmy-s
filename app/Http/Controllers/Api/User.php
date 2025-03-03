<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class User extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = \App\Models\User::where("email", $request->email)->first();

        if(!$user){

            $password = uniqid();
            $password_hashed = bcrypt($password);

            $user = \App\Models\User::create([
                "email" =>  $request->email,
                "name" => $request->name,
                "address" => $request->address,
                "postcode" => $request->postcode,
                "password" => $password_hashed,
            ]);

        }

        $cart = \App\Models\Cart::where("token", request()->cookie("token"))->get();

        foreach ($cart as $_cart) {
            $_cart->user_id = $user->id;
            $_cart->save();
        }

        $result = [
            "status" => true,
            "message" => "User checked."
        ];

        return JsonResponse::fromJsonString(json_encode($result));
    }
}
