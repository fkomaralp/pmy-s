<?php

namespace App\Http\Controllers\Api;

use App\Classes\ImagesStatusUpdater;
use App\Events\ImageStatusUpdated;
use App\Http\Controllers\Controller;
use App\Jobs\ImageAnalysisJob;
use App\Jobs\ImageAnalysisWithoutAnalysis;
use App\Models\Configuration;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Illuminate\Bus\Batch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class ImageAnalysis extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $file = $request->file("image");
        $path = $file->store("storage/event-images-tmp");

        $image = UploadManagerImages::create([
            "event_id" => $request->event_id,
            "file_name" => $file->getClientOriginalName(),
            "path" =>  $path
        ]);

        return JsonResponse::fromJsonString(json_encode(["status" => true, "result" => $image]));
    }

    /**
     * Store a newly created resource in storage with bib numbers.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function startAnalysis(Request $request)
    {
        $images = UploadManagerImages::
        where("event_id", "=", $request->event_id)
            ->whereIn("status", [0,3])
            ->get();

        $jobs = [];

        foreach($images as $image){
            $jobs[] = new ImageAnalysisJob($image);
        }

        $batch = Bus::
        batch($jobs)
            ->then(function (Batch $batch) {

            })
            ->name('Prepare bib number image files')
            ->dispatch();

        if($images->count() > 0) {
            $status = true;
            $result = "Analysis started. " . $images->count() . " Images queued.";
        } else {
            $status = false;
            $result = "No data to analyse.";
        }

        return JsonResponse::fromJsonString(json_encode(["status" => $status, "result" => $result]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function startWithoutAnalysis(Request $request)
    {
        $images = UploadManagerImages::
        where("event_id", "=", $request->event_id)
            ->whereIn("status", [0,3])
            ->get();

        $jobs = [];

        foreach($images as $image){
            $jobs[] = new ImageAnalysisWithoutAnalysis($image);
        }

        $batch = Bus::
        batch($jobs)
            ->then(function (Batch $batch) {

            })
            ->name('Prepare bib number image files')
            ->dispatch();

        if($images->count() > 0) {
            $status = true;
            $result = "Analysis started. " . $images->count() . " Images queued.";
        } else {
            $status = false;
            $result = "No data to analyse.";
        }

        return JsonResponse::fromJsonString(json_encode(["status" => $status, "result" => $result]));
    }

    /**
     * Clear finished records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analysisFinished(Request $request)
    {
        /**
         * Clears status 2 records (it means clear all finished temporary records)
         */
        UploadManagerImages::where("event_id", $request->event_id)->where("status", 2)->delete();

        /**
         * Clear multiple records
         *
         * Some bib numbers are same and their file_name's also is same, we need to delete all of them
         */
        $photos = Photos::where("event_id", $request->event_id)->get();

        foreach ($photos as $photo){
            foreach($photo->bib_numbers as $bib_number){
                $dublicated_bib_numbers = \App\Models\BibNumber::
                    where("photo_id", $photo->photo_id)
                    ->where("bib_number", $bib_number->bib_number)
                    ->get();

                if($dublicated_bib_numbers->count() > 1){
                    $dublicated_bib_numbers->each(function ($bib_number, $key) {
                        $bib_number->delete();
                    });
                }
            }
        }
    }
}
