<?php

namespace App\Http\Livewire\Dashboard\Analysis;

use App\Models\Configuration;
use App\Models\Event;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadManager extends Component
{
    use RedirectsActions, WithFileUploads;

    public $images = [];
    public $event_id = 0;
    public $showingErrors = false;
    public $errors;

    public function render()
    {
        $events = Event::all();

//        $uploaded_old_images = json_encode(Photos::all()->groupBy("event_id")->toArray());

        $MAX_PARALLEL_COUNT = Configuration::getValue("MAX_PARALLEL_COUNT");

        return view('livewire.dashboard.analysis.upload_manager', compact("events", "MAX_PARALLEL_COUNT"));
    }
}

