<?php

namespace App\Events;

use App\Models\UploadManagerImages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageUploadedWithoutAnalysis
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The image instance.
     *
     * @var UploadManagerImages
     */
    public $image;

    /**
     * Create a new event instance.
     *
     * @param  UploadManagerImages  $image
     * @return void
     */
    public function __construct(UploadManagerImages $image)
    {
        $this->image = $image;
    }
}
