<?php

namespace App\Jobs;

use App\Classes\ImagesStatusUpdater;
use App\Models\Configuration;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManagerStatic;

class ImageAnalysisWithoutAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var UploadManagerImages
     */
    private $image;

    /**
     * Create a new job instance.
     *
     * @param UploadManagerImages $image
     */
    public function __construct(UploadManagerImages $image)
    {
        $this->image = $image;
    }

    /**
     * UploadManagerImages.
     *
     * @return void
     * @throws \App\Classes\ImageIptc\Iptc_Exception
     */
    public function handle()
    {
        $image = $this->image;

        ImagesStatusUpdater::progressing($image, $this->batch()->id);

        $image_path = storage_path("app/".$image->path);
        $image_real_file_name = $image->file_name;
        $image_thumbnail_path = public_path("storage/bib_number_thumbnails");

        $save_to_path = $image_thumbnail_path."/".$image_real_file_name;
        $thumbnail_file_path = "/storage/bib_number_thumbnails/".$image_real_file_name;

        $this->generateBibNumberThumbnail($image_path, $save_to_path, "", true);
        $googleCloudStorateResult = $this->googleCloudStorage($image);

        if($googleCloudStorateResult){
            $this->createBibNumber($image, $thumbnail_file_path, []);
        }

        ImagesStatusUpdater::finished($image, $this->batch()->id);

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
        ImagesStatusUpdater::error($this->image, $this->batch()->id);
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
                                                $with_watermark = true,
                                                $thumbnail_width = 720,
                                                $quality = 99,
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
