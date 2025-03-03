<?php

namespace App\Jobs;

use App\Mail\SendImagesToUserEmail;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteUserFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user){
            $email = $user->email;

            $email = preg_replace( '/[\W]/', '', $email);

            $public_path = public_path("user_images");

            $file = $public_path ."/". "picmyrun_".$email.".zip";

            if(\File::exists($file)){
                $last_modified = \File::lastModified($file);

                $d1 = Carbon::parse($last_modified);
                $d2 = Carbon::now('GMT+3');

                if($d1->diff($d2)->i > 15){
                    \File::delete($file);
                }
            }
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
        // Send user notification of failure, etc...
    }
}
