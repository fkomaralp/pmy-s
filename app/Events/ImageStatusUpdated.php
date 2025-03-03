<?php

namespace App\Events;

use App\Models\UploadManagerImages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImageStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The name of the queue on which to place the broadcasting job.
     *
     * @var string
     */
    public $queue = 'image_status_updated_job';

    /**
     * The image instance.
     *
     * @var int
     */
    public $image;

    /**
     * The batch id.
     *
     * @var string
     */
    public $id_batch;

    /**
     * Percentage
     *
     * @var integer
     */
    public $percentage;

    /**
     * Create a new event instance.
     *
     * @param UploadManagerImages $image
     * @param $id_batch
     */
    public function __construct(UploadManagerImages $image, $id_batch)
    {
        $this->image = $image;
        $this->id_batch = $id_batch;
    }

    public function broadcastOn()
    {
        return new Channel('image.status');
    }

    /**
     * Determine if this event should broadcast.
     *
     * @return bool
     */
    public function broadcastWhen()
    {
        return $this->image->status > 0;
    }

    public function broadcastWith()
    {

        $image = $this->image;
        $id_batch = $this->id_batch;

        $batch = DB::table('job_batches')
            ->where('id', 'like', '%'.$id_batch.'%')
            ->first();

//        $this->percentage = $batch->pending_jobs / $batch->total_jobs * 100;
//
//        $this->percentage = 0;

        $this->percentage = ($batch->total_jobs - $batch->pending_jobs) / $batch->total_jobs * 100;

        if((int)$batch->pending_jobs == 0){
            $this->percentage = 100;
        }

        return [
            "percentage" => $this->percentage,
            "image" => $image
        ];
    }
}
