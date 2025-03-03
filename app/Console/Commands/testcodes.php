<?php

namespace App\Console\Commands;

use App\Mail\SendImagesToUserEmail;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Console\Command;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManagerStatic;
use Livewire\Livewire;

class testcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order_number = "1FN88992M33597522";
        $_orders = Order::where("order_number", $order_number)->get();

//        foreach($_orders as $_order){
//            $_order->job_uuid = json_decode($this->job->getRawBody())->uuid;
//            $_order->save();
//        }

        $order = $_orders->first();

        $user = $order->user;

        $email = $user->email;

        $email = preg_replace( '/[\W]/', '', $email);

        $make_directory = true;

        $path = storage_path("app/cloud-storage-tmp/").$email;

        $public_path = public_path("user_images");

        if(!\File::exists($path)){
            $make_directory = \File::makeDirectory($path, 0777);
        }

        //region We need to delete all old files from tmp directory
        $files = glob($path.'/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                @unlink($file); // delete file
            }
        }
        //endregion

        if($make_directory){
//            $downloaded = [];
            foreach($_orders as $order){
                $bib_number = $order->bib_number;

                $event = Event::with(["photos.bib_numbers" => function($query) use($bib_number){
                    $query->where("bib_number", $bib_number);
                }])->find($order->event_id);

                $bib_number_obj = $event->photos->filter(function ($item) { return $item->bib_numbers->count() > 0;});
                foreach($bib_number_obj as $bib_number){
//                    if(!in_array($bib_number->file_name, $downloaded)){
//                        $downloaded[] = $bib_number->file_name;

                    $storage = new StorageClient([
                        'keyFilePath' => config_path("googlevisionkey.json")
                    ]);

                    $bucket = $storage->bucket($order->bucket_name);
                    $object = $bucket->object($bib_number->file_name);

                    if($object->exists()){
                        $parsed_file_name = pathinfo($bib_number->file_name);

                        $filename = $parsed_file_name['filename'];
                        $extension = $parsed_file_name['extension'];

                        $filename = $filename ."-". rand(11111, 99999);

                        $image_path = $path . "/" . $filename .".". $extension;
                        $object->downloadToFile($image_path);

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

            }

        } else {
            throw new \Exception("Cannot create $path directory.");
        }
        $files = \File::files($path);

        $zip = new \ZipArchive();
        if($_orders->count() > 0){
            $file_name = "picmyrun_".$email."-".$order->order_number.".zip";

            if ($zip->open($public_path . "/" . $file_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                \Log::error("Could not create zip archive temp file. SendImagesToUserJob.php");
            }

            foreach ($files as $key => $value){
                $relativeName = basename($value);
//                Log::debug( $relativeName);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();

            try {
                \Mail::to($user->email)->send(new SendImagesToUserEmail(
                    $user->name,
                    $_orders->first(),
                    asset("user_images/".$file_name)
                ));
            }catch(\Exception $e){
                throw $e;
            }
        }
 
        foreach($_orders as $order){
            $order->status = 1;
            $order->job_status = 0;
            $order->job_exception = "";
            $order->save();
        }
        return 0;
    }



    /**
     * Make image HD
     *
     * @param $raw_image string Image path
     * @return mixed
     */
    private function makeImageHD($raw_image)
    {
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

        return $raw_image;
    }
}
