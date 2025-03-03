<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Configuration;
use App\Models\Event as EventModel;
use App\Models\Photos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Event extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EventResource::collection(EventModel::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEventRequest $request)
    {
        $code = 200;

        try {
            EventModel::create([
               "title" => $request->get("title")
            ]);

            $result = EventModel::all();

        } catch (\Exception $e){

            $code = 500;

            $result = [
                "message" => "Error when creating new data.",
                "errors" => [
                    "title"=> [
                        "Error when creating new data."
                    ]
                ]
            ];
        }

        return JsonResponse::fromJsonString(json_encode($result), $code);
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
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchByName(Request $request)
    {
        $keyword = $request->get("keyword");

        $result = EventModel::where("title", "LIKE", '%'.$keyword.'%')->where("status", 1)->get();
        //with(["country", "city"])->where("title", "LIKE", '%'.$keyword.'%')->get();

        return JsonResponse::fromJsonString(json_encode($result), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function check(Request $request)
    {
        $event_access_code = $request->get("event_access_code");
        $bib_number = $request->get("bib_number");
        $event_id = $request->get("event_id");

        $event = \App\Models\Event::find($event_id);
//        $EVENTS_PASSWORD_PROTECTED = boolval(Configuration::getValue("EVENTS_PASSWORD_PROTECTED"));

        if($event->protected && $event_access_code == null){

            $result = [
                "status" => false,
                "message" => "Please enter event password.",
            ];

            return JsonResponse::fromJsonString(json_encode($result), 200);
        }

        if($event->protected && ($event_access_code != $event->event_code)){

            $result = [
                "status" => false,
                "message" => "Wrong event password.",
            ];

            return JsonResponse::fromJsonString(json_encode($result), 200);
        }

//        $bib_number_results = Photos::where([
//            "event_id" => $event_id
//        ])->get();

        $bib_numbers = \App\Models\BibNumber::where("bib_number", $bib_number)->get();

        $_bib_number_result = collect();

        foreach($bib_numbers as $_bib_number){
            if($_bib_number->photo){
                if($_bib_number->photo->event->id === $event_id){

                    $_bib_number_result->push($_bib_number);
                }
            }
        }

//        $_bib_number_result = collect();
//
//        foreach($bib_number_results as $bib_number_result) {
//            if (isset($bib_number_result->bib_numbers)) {
//                $_bib_number_result->push($bib_number_result->bib_numbers->where("bib_number", $bib_number));
//            }
//        }

        if($_bib_number_result->count() == 0){
            $result = [
                "status" => false,
                "message" => "No images found.",
            ];

            return JsonResponse::fromJsonString(json_encode($result), 200);
        }

        $result = [
            "status" => true,
            "redirect_to" => route("frontend.event.access.preview", ["event_id" => $event_id, "bib_number" => $bib_number]),
        ];

        return JsonResponse::fromJsonString(json_encode($result), 200);
    }
}
