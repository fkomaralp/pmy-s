<?php

namespace App\Classes;

use App\Events\ImageStatusUpdated;
use App\Jobs\ClearAnalysedImages;
use App\Models\BibNumber;
use App\Models\Configuration;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\DB;

class ImagesStatusUpdater
{
    public static function progressing($image, $id_batch) {
        $image->update([
            "status" => 1
        ]);

//        DB::commit();

        ImageStatusUpdated::dispatch($image, $id_batch);

        return true;
    }

    public static function finished($image, $id_batch) {
        $image->update([
            "status" => 2
        ]);

//        DB::commit();

//        $is_finished_all = false;
//
//        $not_finished_count = UploadManagerImages::
//        where("event_id", $image->event_id)
//            ->where(function($query){
//                return $query->where("status", "=", 0)
//                    ->orWhere("status", "=", 1);
//            })->get()->count();
//
//        $total = UploadManagerImages::
//        where("event_id", $image->event_id)
//            ->get()->count();
//
//        if($not_finished_count === 0){
//            $is_finished_all = true;
//        }
//
//        $completed = $total - $not_finished_count;
//
//        $completed_label = $completed . "/" . $total;

        ImageStatusUpdated::dispatch($image, $id_batch);

//        if($is_finished_all){
//            UpdateBibNumberJsonToCloud::update($image->event);
//
//            Configuration::updateOrCreate(["name" => "SKIP_ANALYSING_STATUS"], ["value" => 0]);
//            Configuration::updateOrCreate(["name" => "ANALYSING_STATUS"], ["value" => 0]);
//            Configuration::updateOrCreate(["name" => "ANALYSING_MANUAL_TAGGING_STATUS"], ["value" => 0]);
//
//        }

        ClearAnalysedImages::dispatch($image)
            ->delay(now()->addDays(1));

        return true;
    }

    public static function error($image, $id_batch) {
        $image->update([
            "status" => 3
        ]);

//        DB::commit();

        ImageStatusUpdated::dispatch($image, $id_batch);

        return true;
    }
}
