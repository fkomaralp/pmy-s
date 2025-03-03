<?php

namespace App\Jobs;

use App\Classes\Helper;
use App\Classes\ImagesStatusUpdater;
use App\Models\Configuration;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic;

class ImageAnalysisJob implements ShouldQueue
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
    public function __construct(UploadManagerImages $image, $with_thumbnails = true)
    {
        $this->image = $image;
        $this->with_thumbnails = $with_thumbnails;
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

        $image_path = storage_path("app/".$image_object->path);
        $image_real_file_name = $image_object->file_name;
        $image_thumbnail_path = public_path("storage/bib_number_thumbnails/".$image_object->event->bucket_name);

        if(!file_exists($image_thumbnail_path)){
            @mkdir($image_thumbnail_path);
        }

//        $image = file_get_contents($image_path);
        $image = fopen($image_path, 'r');

        $response = $client->textDetection($image);
        $data = $response->serializeToJsonString();

        $bib_numbers = [];
        $bib_number_block = "";

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

        $save_to_path = $image_thumbnail_path."/".$image_real_file_name;
        $thumbnail_file_path = "/storage/bib_number_thumbnails/".$image_object->event->bucket_name."/".$image_real_file_name;

        if($this->with_thumbnails){
            $this->generateBibNumberThumbnail($image_path, $save_to_path, $bib_number_block);
        }

        $googleCloudStorateResult = $this->googleCloudStorage($image_object);

        if($googleCloudStorateResult){
            $this->createBibNumber($image_object, $thumbnail_file_path, $bib_numbers);
        }

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

    /**
     * Bib number thumbnail generator.
     * This function is generates raw image's thumbnail.
     * And that thumbnail will use in front-end's bib number search results.
     * Thumbnails are includes watermark and bib number block on IPTC data.
     *
     * @param $image_path
     * @param $save_to_path
     * @param $bib_number_block
     * @param int $thumbnail_width
     * @param int $quality
     * @param bool $with_watermark
     * @param int $watermark_width
     * @param int $watermark_quality
     * @throws \App\Classes\ImageIptc\Iptc_Exception
     */
    private function generateBibNumberThumbnail($image_path,
                                                $save_to_path,
                                                $bib_number_block,
                                                $thumbnail_width = 720,
                                                $quality = 99,
                                                $with_watermark = true,
                                                $watermark_width = 100,
                                                $watermark_quality = 99)
    {
        $img = ImageManagerStatic::make($image_path);

        $img->orientate()->resize($thumbnail_width, $thumbnail_width, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($save_to_path, $quality);

        if($with_watermark){
            $watermark_image_path = ltrim(Configuration::getValue("THUMBNAIL_WATERMARK"), '/');

            $watermark_image_path = public_path($watermark_image_path);

            $watermark_image = ImageManagerStatic::make($watermark_image_path);
            $watermark_image->orientate()->opacity(60)->resize($watermark_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img = ImageManagerStatic::make($save_to_path);
            $img->orientate()->fill($watermark_image);
            $img->save($save_to_path, $watermark_quality);
        }

//        $iptc = new Iptc($save_to_path);
//        $iptc->set(Iptc::SPECIAL_INSTRUCTIONS, [$bib_number_block]);
//        $iptc->write();
//
//        $iptc = new Iptc($image_path);
//        $iptc->set(Iptc::SPECIAL_INSTRUCTIONS, [$bib_number_block]);
//        $iptc->write();

        $img = null;
        $watermark_image = null;
        $iptc = null;
    }

    private function googleCloudStorage(UploadManagerImages $image)
    {
        $storage = new StorageClient([
            "keyFile" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        try {
            $bucket = $storage->bucket($image->event->bucket_name);

            if(!$bucket->exists()){
                $storage->createBucket($image->event->bucket_name);
            }

        } catch (\Exception $e){
            throw ValidationException::withMessages(['title' => "Google Cloud : " . json_decode($e->getMessage())->error->message]);
        }

        $bucket = $storage->bucket($image->event->bucket_name);

        $image_full_path = storage_path("app/".$image->path);

        $bucket->upload(
            fopen($image_full_path, 'r'),
            [
                "name" => $image->file_name
            ]
        );

        return true;
    }

    private function createBibNumber(UploadManagerImages $image, $thumbnail_file_path, $bib_numbers)
    {
        $data = [
            "file_name" => $image->file_name,
            "thumbnail" => $thumbnail_file_path,
            "event_id" => $image->event_id
        ];

        $photo = Photos::create($data);

        foreach(array_unique($bib_numbers) as $bib_number){
            $photo->bib_numbers()->create(["bib_number" => $bib_number]);
        }

        $data = null;

        return true;
    }
}
