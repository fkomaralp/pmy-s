<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendImagesRequest;
use App\Models\Configuration;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Configurations extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $value = $request->get("value");

        if($request->hasFile("data")){
            $path = $request->file("data")->store("public");
            $value = "/".str_replace("public", "storage", $path);
        }

        Configuration::updateOrCreate(["name" => $request->get("name")], ["value" => $value]);

        $result = [
            "status" => true,
            "message" => "Settings saved.",
        ];

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * Display the specified resource.
     *
     * @param string $name
     * @return JsonResponse
     */
    public function show(string $name)
    {
        $config = Configuration::getValue($name);

        $result = [
            "status" => true,
            "value" => $config,
        ];

        return JsonResponse::fromJsonString(json_encode($result));
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
