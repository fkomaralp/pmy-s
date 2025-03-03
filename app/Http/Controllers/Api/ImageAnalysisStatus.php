<?php

namespace App\Http\Controllers\Api;

use App\Events\ImageUploaded;
use App\Http\Controllers\Controller;
use App\Models\UploadManagerImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageAnalysisStatus extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $file_name = $request->get("file_name");
        $event_id = $request->get("event_id");

        $result = "Waiting";
        $is_finished = false;
        $is_finished_all = false;

        $images = UploadManagerImages::all();

        $image = $images
            ->where("file_name", $file_name)
            ->where("event_id", $event_id)
        ->first();

        $not_finished_count = UploadManagerImages::
            where("event_id", $event_id)
            ->where(function($query){
                return $query->where("status", "=", 0)
                ->orWhere("status", "=", 1);
            })->get()->count();

        if($not_finished_count === 0){
            $is_finished_all = true;
        }

        $status = 2;

        if($image){
            $status = $image->status;

            switch ($status){
                case 1:
                    $result = "In Progress";
                    break;
                case 2:
                    $result = "Finished";
                    $is_finished = true;
                    break;
                case 3:
                    $result = "Error";
                    $is_finished = true;
                    break;
            }
        } else {
            $result = "Finished";
            $is_finished = true;
        }

        return JsonResponse::fromJsonString(json_encode(
            [
                "status" => true,
                "_result" => $status,
                "result" => $result,
                "is_finished" => $is_finished,
                "is_finished_all" => $is_finished_all
            ]));
    }
}
