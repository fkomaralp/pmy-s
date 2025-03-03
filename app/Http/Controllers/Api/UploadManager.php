<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UploadManagerImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Wikimedia\XMPReader\Reader;

class UploadManager extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $event = \App\Models\Event::find($request->event_id);

        $file = $request->file("image");

        $event_id = (int)$request->event_id;

        $file_exists = DB::table("upload_manager_images")
            ->where("event_id", "=", $event_id)
            ->where("file_name", "=", $file->getClientOriginalName())
            ->where("status", "=", 0)
            ->get();

        if($file_exists->count() <= 0) {
            $path = $file->store("event-images-tmp/" . $event->bucket_name);

//            $xmp_data = $this->getXmpData($path,50000);
//
//            $xmp = new Reader( null, $path );
//            $xmp->parse($xmp_data);
//            $DateTimeOriginal = $xmp->getResults()["xmp-exif"]["DateTimeOriginal"];

            $image = UploadManagerImages::create([
                "event_id" => (int)$request->event_id,
                "file_name" => $file->getClientOriginalName(),
                "path" => $path,
                "status" => 0
            ]);
        } else {
            return JsonResponse::fromJsonString(json_encode(["status" => false, "result" => null, "errors" => [$file->getClientOriginalName() . " Image is uploaded before but never analysed."]]), 200);
        }

//        return JsonResponse::fromJsonString(json_encode(["status" => false, "result" => null, "errors" => [$file->getClientOriginalName() . " Image is uploaded before but never analysed."]]), 500);

        return JsonResponse::fromJsonString(json_encode(["status" => true, "result" => $image]));
    }

//    function getXmpData($filename, $chunk_size = 50000){
//        $buffer = NULL;
//        if (($file_pointer = fopen($filename, 'r')) === FALSE) {
//            dd("hata");
//        }
//
//        $chunk = fread($file_pointer, $chunk_size);
//        if (($posStart = strpos($chunk, '<x:xmpmeta')) !== FALSE) {
//            $buffer = substr($chunk, $posStart);
//            $posEnd = strpos($buffer, '</x:xmpmeta>');
//            $buffer = substr($buffer, 0, $posEnd + 12);
//        }
//
//        fclose($file_pointer);
//
//        // recursion here
//        if(!strpos($buffer, '</x:xmpmeta>')){
//            $buffer = $this->getXmpData($filename, $chunk_size*2);
//        }
//
//        return $buffer;
//    }
}
