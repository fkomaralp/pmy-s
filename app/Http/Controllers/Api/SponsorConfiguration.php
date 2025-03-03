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

class SponsorConfiguration extends Controller
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
        $SPONSOR_ORIENTATION = $request->get("orientation");
        $SPONSOR_OPACITY = $request->get("opacity");
        $SPONSOR_POSITION = $request->get("position");
        $SPONSOR_WIDTH = $request->get("width");

        $SPONSOR_IMAGE = "";

        if($request->hasFile("sponsor_image")){
            $path = $request->file("sponsor_image")->store("public");
            $SPONSOR_IMAGE = "/".str_replace("public", "storage", $path);
        }

        Configuration::updateOrCreate(["name" => "SPONSOR_IMAGE"], ["value" => $SPONSOR_IMAGE]);
        Configuration::updateOrCreate(["name" => "SPONSOR_ORIENTATION"], ["value" => $SPONSOR_ORIENTATION]);
        Configuration::updateOrCreate(["name" => "SPONSOR_OPACITY"], ["value" => floatval($SPONSOR_OPACITY) * 100]);
        Configuration::updateOrCreate(["name" => "SPONSOR_POSITION"], ["value" => $SPONSOR_POSITION]);
        Configuration::updateOrCreate(["name" => "SPONSOR_WIDTH"], ["value" => $SPONSOR_WIDTH]);

        $result = [
            "status" => true,
            "message" => "Sponsor settings saved.",
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
