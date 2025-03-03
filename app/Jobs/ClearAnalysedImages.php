<?php

namespace App\Jobs;

use App\Models\UploadManagerImages;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClearAnalysedImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 1;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = $this->image;

        \Log::info("ClearAnalysedImages - ". $image->id . " işlendi. Eski verinin silinmesi için çalışıyor.");

        if($image){
            do {
                if($image->status === 2){

                    $image_path = storage_path("app/".$image->path);
                    @unlink($image_path);

                    \Log::info("ClearAnalysedImages - ". $image->id . " . Eski veri silindi.");

                    $image->delete();

                    break;
                }
            } while(0);

        } else {
            throw new \Exception("Image data not found.");
        }
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
    }
}
