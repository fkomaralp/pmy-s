<?php

namespace App\Jobs;

use App\Classes\Helper;
use App\Classes\ImageIptc\Iptc;
use App\Classes\ImagesStatusUpdater;
use App\Events\ImageUploaded;
use App\Models\BibNumber;
use App\Models\Configuration;
use App\Models\Event;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Service\CloudSearch\Photo;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic;

class ImageReAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var UploadManagerImages
     */
    public $image;
    /**
     * @var bool|mixed
     */
    private $with_thumbnails;

    /**
     * Create a new job instance.
     *
     * @param UploadManagerImages $image
     * @param bool $with_thumbnails
     */
    public function __construct(UploadManagerImages $image)
    {
        $this->image = $image;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $image_object = $this->image;

        ImagesStatusUpdater::progressing($image_object, $this->batch()->id);

        $client = new ImageAnnotatorClient([
            "credentials" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        $event = Event::find($image_object->event_id);

        $photo = $event->photos->where("file_name", $image_object->file_name)
        ->first();

        $response = $client->textDetection("gs://".$event->bucket_name."/".$photo->file_name);
        $data = $response->serializeToJsonString();

        $bib_numbers = [];

        $json_test = Helper::isJson($data);

        if($json_test){
            $data = json_decode($data);

            if(isset($data->textAnnotations) && is_array($data->textAnnotations) && count($data->textAnnotations) > 0){
                $description = $data->textAnnotations[0]->description;

                $description_array = explode("\n", $description);

                foreach($description_array as $bib_number){
                    preg_match('/^\d+$/', $bib_number, $matches);

                    $result = collect();

                    if($image_object->event->filters->count() > 0){

                        $filters = $image_object->event->filters->map(function($val, $key){
                            return ["from" => $val["filter_from"], "to" => $val["filter_to"]];
                        });

                        $result = $filters->filter(function($val, $key) use ($bib_number){
                            if((int)$val["from"] === 0 && (int)$val["to"] === 0){
                                return true;
                            } else {
                                return (int)$bib_number >= (int)$val["from"] && (int)$bib_number <= (int)$val["to"];
                            }
                        });

                    }

                    if($matches && ($result->count() > 0 || $image_object->event->filters->count() === 0)){
                        $bib_numbers[] = $bib_number;
                    }
                }

                if(count($bib_numbers) > 0){
                    $bib_number_block = implode("-", $bib_numbers);
                }
            }
        }


        $this->createBibNumber($image_object, $bib_numbers);

        ImagesStatusUpdater::finished($image_object, $this->batch()->id);

        $image = null;
        $client = null;
        $response = null;
        $data = null;
        $bib_numbers = null;
        $bib_number_block = null;
        $googleCloudStorateResult = null;
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed($exception)
    {
        ImagesStatusUpdater::finished($this->image, $this->batch()->id);
    }

    private function createBibNumber(UploadManagerImages $image, $bib_numbers)
    {

        $photo = Photos::with("bib_numbers")
            ->where("file_name", $image->file_name)
            ->where("event_id", $image->event_id)
            ->get();

        foreach(array_unique($bib_numbers) as $bib_number){
            if($photo->first()->bib_numbers->where("bib_number", $bib_number)->count() === 0){
                $photo->first()->bib_numbers()->create(["bib_number" => $bib_number]);
            }
        }

        $data = null;

        return true;
    }
}
