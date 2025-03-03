<?php

namespace App\Jobs;

use App\Classes\Helper;
use App\Mail\SendImagesToUserEmail;
use App\Models\Configuration;
use App\Models\Event;
use App\Models\Order;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic;


class SendImagesToUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
//        $this->onQueue('send_images_to_user');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $_orders = Order::where("order_number", $this->order->order_number)->get();

        foreach($_orders as $_order){
            $_order->job_uuid = json_decode($this->job->getRawBody())->uuid;
            $_order->save();
        }

        $user = $this->order->user;

        $email = $user->email;

        $email_file = preg_replace( '/[\W]/', '', $email);

        $make_directory = true;

        $path = storage_path("app/cloud-storage-tmp/").$email_file;

        $public_path = public_path("user_images");

        if(!\File::exists($path)){
            $make_directory = \File::makeDirectory($path, 0777);
        }

        if($make_directory){
//            $downloaded = [];
            $orders = Order::
            where("user_id", $user->id)
                ->where("status", 0)
                ->where("job_status", 0)
                ->get();

            $jobs = [];

            foreach($orders as $order){
                $bib_number = $order->bib_number;

                $event = Event::with(["photos.bib_numbers" => function($query) use($bib_number){
                    $query->where("bib_number", $bib_number);
                }])->find($order->event_id);

                $bib_number_obj = $event->photos->filter(function ($item) { return $item->bib_numbers->count() > 0;});
                foreach($bib_number_obj as $bib_number){
                    $jobs[] = new PrepareImageFileJob($order, $bib_number, $path);
                }
            }

            $file_name = "picmyrun_".$email."-".$order->order_number.".zip";

            \Log::debug( "save path => " . $public_path . "/" . $file_name);

            $batch = Bus::
            batch($jobs)
                ->then(function (Batch $batch) use($path, $orders, $email, $order, $public_path, $user ) {

                Log::debug( "batch finished ziping files");
                $files = \File::files($path);

                $zip = new \ZipArchive();
                Log::debug( "file count ".$orders->count()." files");
                if($orders->count() > 0){
                    $file_name = "picmyrun_".$email."-".$order->order_number.".zip";

                    if ($zip->open($public_path . "/" . $file_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                        \Log::error("Could not create zip archive temp file. SendImagesToUserJob.php");
                    }

                    foreach ($files as $key => $value){
                        $relativeName = basename($value);
                        Log::debug( $relativeName);
                        $zip->addFile($value, $relativeName);
                    }
                    $zip->close();

                    //region We need to delete all old files from tmp directory
                    Helper::deleteDirectory($path);
                    //endregion

                    \Log::debug($user->email . " Sending...");

                    try {

                        \Mail::to($user->email)->send(new SendImagesToUserEmail(
                            $user->name,
                            $orders->first(),
                            asset("user_images/".$file_name)
                        ));

                        \Log::debug($user->email . " Sent.");

                    }catch(\Exception $e){
                        \Log::debug('Mail not sent.');
                        throw $e;
                    }

//                    DeleteSentZip::dispatch($public_path . "/" . $file_name)->delay(now()->addMonths(1));
                }

                foreach($orders as $order){
                    $order->status = 1;
                    $order->job_status = 0;
                    $order->job_exception = "";
                    $order->save();
                }


            })->name('Prepare bib number image files')->dispatch();

        } else {
            throw new \Exception("Cannot create $path directory.");
        }

    }

    /**
     * The job failed to process.
     *
     * @param  mixed  $exception
     * @return void
     */
    public function failed($exception)
    {
//        Log::debug($this->order);

        $order = Order::find($this->order->id);
        $order->job_status = 1;
        $order->job_exception = $exception->getMessage();
        $order->save();
    }
}
