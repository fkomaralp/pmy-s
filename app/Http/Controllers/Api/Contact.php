<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendContactRequest;
use App\Mail\SendContactQuestion;
use App\Models\Configuration;
use Illuminate\Http\JsonResponse;

class Contact extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param SendContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sendMessage(SendContactRequest $request)
    {
        try {
            \Mail::to(Configuration::getValue("EMAIL"))->send(new SendContactQuestion(
                $request->get("email"),
                $request->get("fullname"),
                $request->get("question")
            ));
        }catch(\Exception $e){
            throw $e;
        }

        $result = [
            "status" => true,
            "message" => "Your question sent to administrator."
        ];

        return JsonResponse::fromJsonString(json_encode($result));
    }
}
