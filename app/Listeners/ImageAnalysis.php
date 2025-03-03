<?php

namespace App\Listeners;

use App\Classes\Helper;
use App\Classes\ImageIptc\Iptc;
use App\Classes\ImagesStatusUpdater;
use App\Events\ImageUploaded;
use App\Models\BibNumber;
use App\Models\Configuration;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic;
use Wikimedia\XMPReader\Reader;

class ImageAnalysis implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'default';
    public $afterCommit = true;

    /**
     * Handle the event.
     *
     * @param  \App\Events\ImageUploaded  $event
     * @return void
     */
    public function handle(ImageUploaded $event)
    {
        $client = new ImageAnnotatorClient([
            "credentials" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        $image_path = storage_path("app/".$event->image->path);
        $image_real_file_name = $event->image->file_name;
        $image_thumbnail_path = public_path("storage/bib_number_thumbnails");

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
                    if($matches){
                        $bib_numbers[] = $bib_number;
                    }
                }

                if(count($bib_numbers) > 0){
                    $bib_number_block = implode("-", $bib_numbers);
                }
            }
        }

        if(count($bib_numbers) > 0){

            $save_to_path = $image_thumbnail_path."/".$image_real_file_name;
            $thumbnail_file_path = "/storage/bib_number_thumbnails/".$image_real_file_name;

            $this->generateBibNumberThumbnail($image_path, $save_to_path, $bib_number_block);
            $googleCloudStorateResult = $this->googleCloudStorage($event->image);

            if($googleCloudStorateResult){
//                 @unlink($image_path);
                 $this->createBibNumber($event->image, $thumbnail_file_path, $bib_numbers);
            }
        }

        \Log::info("ImageAnalysis - handle - ". $event->image->id . " işlendi. Toplam bib numara sayısı : " . count($bib_numbers) );

        ImagesStatusUpdater::finished($event->image);

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
     * @param  \App\Events\ImageUploaded  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(ImageUploaded $event, $exception)
    {
        ImagesStatusUpdater::finished($event->image);
    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return bool
     */
    public function shouldQueue(ImageUploaded $event)
    {
        return true;
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
            $img->orientate()->insert($watermark_image, 'center', 10, 10);
            $img->save($save_to_path, $watermark_quality);
        }

        $iptc = new Iptc($save_to_path);
        $iptc->set(Iptc::SPECIAL_INSTRUCTIONS, [$bib_number_block]);
        $iptc->write();

        $iptc = new Iptc($image_path);
        $iptc->set(Iptc::SPECIAL_INSTRUCTIONS, [$bib_number_block]);
        $iptc->write();

        $img = null;
        $watermark_image = null;
        $iptc = null;
    }

    private function googleCloudStorage(UploadManagerImages $image)
    {
        $storage = new StorageClient([
            "keyFile" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

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

        foreach($bib_numbers as $bib_number){
            $photo->bib_numbers()->create(["bib_number" => $bib_number]);
        }

        $data = null;

        return true;
    }
}
