<?php

namespace App\Jobs;

use App\Models\BibNumber;
use App\Models\Configuration;
use App\Models\Order;
use App\Models\Photos;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic;

class PrepareImageFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var Order
     */
    private $order;
    /**
     * @var BibNumber
     */
    private $bib_number;
    /**
     * @var string
     */
    private $path;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param Photos $bib_number
     * @param string $path
     */
    public function __construct(Order $order, Photos $bib_number, string $path)
    {
        $this->order = $order;
        $this->bib_number = $bib_number;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storage = new StorageClient([
            'keyFilePath' => config_path("googlevisionkey.json")
        ]);

        $order = $this->order;
        $bib_number = $this->bib_number;
        $path = $this->path;

        $bucket = $storage->bucket($order->bucket_name);
        $object = $bucket->object($bib_number->file_name);

        if($object->exists()){
            $parsed_file_name = pathinfo($bib_number->file_name);

            $filename = $parsed_file_name['filename'];
            $extension = $parsed_file_name['extension'];

            $filename = $filename ."-". rand(11111, 99999);

            $image_path = $path . "/" . $filename .".". $extension;
            $object->downloadToFile($image_path);

            Log::debug($image_path . "downlodaded");

            if(file_exists($image_path)) {

                // FREE + HD
                if($order->orderPrice && (int)$order->orderPrice->price < 1 && $order->orderPrice->type == 0){
                    $image_path = $this->makeImageHD($image_path);
                    $this->addWatermarkToFreeImage($image_path);
                }

                // FREE + Sponsored
                if($order->orderPrice && (int)$order->orderPrice->price < 1 && $order->orderPrice->type == 2){
                    $image_path = $this->makeImageHD($image_path);
                    $this->addSponsoredWatermarkToImage($image_path, $order->event);
                }

                // PAID + HD
                if($order->orderPrice && (int)$order->orderPrice->price > 1 && $order->orderPrice->type == 0){
                    $this->makeImageHD($image_path);
                }

            }
        }
//                        else {
//                            throw new \Exception("File not found on bucket (".$order->event->title.").");
//                        }

//                    }
    }



    /**
     * Add watermark to free image
     *
     * @param $raw_image string Image path
     * @return mixed
     */
    private function addWatermarkToFreeImage($raw_image)
    {
        $img = ImageManagerStatic::make($raw_image);
        $img->orientate()->insert(public_path(Configuration::getValue("WATERMARK_FOR_FREE_PRICE")), 'bottom-right', 20, 20);
        $img->save($raw_image, 99);
    }

    /**
     * Make image HD
     *
     * @param $raw_image string Image path
     * @return mixed
     */
    private function makeImageHD($raw_image)
    {
        Log::debug($raw_image);
        $img = ImageManagerStatic::make($raw_image);

        $width = 1920;
        $height = null;

        if($img->getWidth() >= $img->getHeight()){
            $width = 1920;
            $height = null;
        }

        if($img->getWidth() < $img->getHeight()){
            $width = null;
            $height = 1920;
        }

        $img->orientate()->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($raw_image, 99);
        Log::debug("$raw_image saved");


        return $raw_image;
    }

    /**
     * Add sponsored watermark to image
     *
     * @param $raw_image string Image path
     * @return string
     */
    public function addSponsoredWatermarkToImage($raw_image, $event)
    {
        $SPONSOR_POSITION = Configuration::getValue("SPONSOR_POSITION");

        switch($SPONSOR_POSITION){
            case 'top-0 left-0':
                $position = 'top-left';
                break;
            case 'inset-x-0 mx-auto':
                $position = 'top';
                break;
            case 'top-0 right-0':
                $position = 'top-right';
                break;
            case 'inset-y-0 my-auto':
                $position = 'left';
                break;
            case 'inset-y-0 my-auto right-0':
                $position = 'right';
                break;
            case 'bottom-0 left-0':
                $position = 'bottom-left';
                break;
            case 'bottom-0 inset-x-0 mx-auto':
                $position = 'bottom';
                break;
            case 'bottom-0 right-0':
                $position = 'bottom-right';
                break;

            default:
                $position = 'center';
                break;
        }

        $img = ImageManagerStatic::make($raw_image);

        $FIT_IMAGE_TO_WIDTH = boolval(Configuration::getValue("FIT_IMAGE_TO_WIDTH"));

        $sponsored_image = "";

        if($img->getWidth() >= $img->getHeight()){
            $sponsored_image = $event->sponsor_horizontal_image;
        }

        if($img->getWidth() < $img->getHeight()){
            $sponsored_image = $event->sponsor_vertical_image;
        }

        if($sponsored_image === ""){
            throw new \Exception("Raw image file not found.");
        }

        $sponsor_watermark_image_path = public_path($sponsored_image);

        if(!file_exists($sponsor_watermark_image_path)){
            throw new \Exception("Sponsored watermark image not found.");
        }

        $sponsor_watermark_image = ImageManagerStatic::make($sponsor_watermark_image_path);

        $sponsor_width = (int)Configuration::getValue("SPONSOR_WIDTH");

        if($FIT_IMAGE_TO_WIDTH){
            $sponsor_width = $img->width();
        }

        $sponsor_watermark_image->resize($sponsor_width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

//        $sponsor_watermark_image->opacity(floatval(Configuration::getValue("SPONSOR_OPACITY")));
//        $sponsor_watermark_image->rotate((int)Configuration::getValue("SPONSOR_ORIENTATION"));

        $img->orientate()->insert($sponsor_watermark_image, $position, 100);
        $img->save($raw_image, 99);
//        Log::debug($raw_image . " addSponsoredWatermarkToImage saved");

        return $raw_image;
    }

}
