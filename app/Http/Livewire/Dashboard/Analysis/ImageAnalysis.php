<?php

namespace App\Http\Livewire\Dashboard\Analysis;

use App\Events\ImageUploaded;
use App\Models\Configuration;
use App\Models\Event;
use App\Models\UploadManagerImages;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImageAnalysis extends Component
{
    use RedirectsActions, WithFileUploads;

    public $images = [];
    public $event_id = 0;

//    protected $listeners = [
//        'refreshComponent' => '$refresh'
//    ];

    public function updatedEventId($value)
    {
        $this->images = UploadManagerImages::
        where("event_id", (int)$value)
        ->get()->sortBy([
                ['id', 'desc']
            ])
            ->toArray();



        $status = [];
        foreach($this->images as $image){
            if($image["status"] === 2){
                $status[$image["file_name"]] = "Finished";
            }
            if($image["status"] === 1){
                $status[$image["file_name"]] = "In Progress";
            }
            if($image["status"] === 0){
                $status[$image["file_name"]] = "Waiting";
            }
        }

        $not_finished_count = UploadManagerImages::
        where("event_id", (int)$value)
            ->where(function($query){
                return $query->where("status", "=", 0)
                    ->orWhere("status", "=", 1);
            })->get()->count();

        $total = UploadManagerImages::
        where("event_id", (int)$value)
            ->get()->count();

        $completed = $total - $not_finished_count;

        $completed_label = $completed . "/" . $total;

        $this->emit('updatePageVariables', ['images' => $this->images, 'status' => $status, "completed_label" => $completed_label]);
    }

    public function analysingStatusUpdate()
    {
        Configuration::updateOrCreate(["name" => "ANALYSING_STATUS"], ["value" => 1]);
    }

    public function skipToManualTaggingStatusUpdate()
    {
        Configuration::updateOrCreate(["name" => "SKIP_ANALYSING_STATUS"], ["value" => 1]);
    }

    public function render()
    {
        $events = Event::all();

        $IMAGE_ANALYSIS_MAX_PARALLEL_COUNT = Configuration::getValue("IMAGE_ANALYSIS_MAX_PARALLEL_COUNT");
        $ANALYSING_STATUS = Configuration::getValue("ANALYSING_STATUS");
        $SKIP_ANALYSING_STATUS = Configuration::getValue("SKIP_ANALYSING_STATUS");

        return view('livewire.dashboard.analysis.analysis', compact("events", "IMAGE_ANALYSIS_MAX_PARALLEL_COUNT", "ANALYSING_STATUS", "SKIP_ANALYSING_STATUS"));
    }
}

