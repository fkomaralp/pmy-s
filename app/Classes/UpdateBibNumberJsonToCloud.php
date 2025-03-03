<?php

namespace App\Classes;

use App\Models\Event;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;

class UpdateBibNumberJsonToCloud
{
    /**
     * @param Event $event
     * @return bool
     */
    public static function update(Event $event) {

        $file_path = public_path("storage/bib_number_thumbnails/".$event->bucket_name."/data.json");

        if(!file_exists(public_path("storage/bib_number_thumbnails/".$event->bucket_name))){
            @mkdir(public_path("storage/bib_number_thumbnails/".$event->bucket_name));
        }

        $photos = Photos::with("bib_numbers")->where("event_id", $event->id)->get();
        $content = [];
        foreach($photos as $photo){
            $bib_number_block = implode("-", $photo->bib_numbers->pluck("bib_number")->toArray());
            $content[$photo->event->bucket_name][$photo->file_name] = $bib_number_block;
        }

        file_put_contents($file_path, json_encode($content));


        $storage = new StorageClient([
            "keyFile" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        $bucket = $storage->bucket($event->bucket_name);

        $bucket->upload(
            fopen($file_path, 'r'),
            [
                "name" => "data.json"
            ]
        );

        return true;
    }
}
