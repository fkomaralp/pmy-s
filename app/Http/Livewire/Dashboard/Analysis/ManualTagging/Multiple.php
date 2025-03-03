<?php

namespace App\Http\Livewire\Dashboard\Analysis\ManualTagging;

use App\Jobs\ClearUnnecessaryImageFiles;
use App\Models\BibNumber;
use App\Models\Event;
use App\Models\Photos;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class Multiple extends Component
{
    use RedirectsActions, Actions;

    protected $listeners = ["refreshPage" => "render"];

    public $delete_bib_number;
    public $new_bib_number;
    public $events;
    public $event_id = 0;

    public function mount()
    {
        $this->events = Event::where("status", 1)->get();
    }

    public function DeleteOnce()
    {
        if($this->event_id <= 0){
            $this->notification()->error(
                'Error',
                'Please select an event.'
            );
            return false;
        }

        $bib_numbers = explode(",",$this->delete_bib_number);
        $record_count = 0;
        $photos = Photos::with("bib_numbers")->where('event_id', $this->event_id)->get();

        foreach($photos as $photo){
            if(in_array("all", $bib_numbers)){
                foreach($photo->bib_numbers as $bib_number){
                    $record_count++;
                    $bib_number->delete();
                }
            } else {
                foreach($photo->bib_numbers as $bib_number){
                    if(in_array($bib_number->bib_number, $bib_numbers)){
                        $record_count++;
                        $bib_number->delete();
                    }
                }
            }
        }

        $this->notification()->info(
            $title = 'Deleted',
            $description = 'Total '.$record_count.' bib numbers are deleted.'
        );
    }

    public function AddNewBibNumber()
    {

        if($this->event_id <= 0){
            $this->notification()->error(
                'Error',
                'Please select an event.'
            );
            return false;
        }

        $bib_numbers = explode(",",$this->new_bib_number);
        $added_cnt = 0;

        $succ_added = [];
        $fail_added = [];


        $photos = Photos::with("bib_numbers")->where('event_id', $this->event_id)->get();
        foreach ($photos as $photo){
            foreach ($bib_numbers as $bib_number){
                if($photo->bib_numbers->where("bib_number", $bib_number)->count() <= 0){
                    $added_cnt++;
                    $photo->bib_numbers()->create([
                        "bib_number" => $bib_number
                    ]);
                    $succ_added[$bib_number] = "Bib number ($bib_number) created for $added_cnt records.";
                } else {
                    $fail_added[$bib_number] = "Bib number ($bib_number) already added.";
                }
            }
        }

        $message = "";

        foreach ($succ_added as $item){
            $message .= $item . "\n";
        }

        foreach ($fail_added as $item){
            $message .= $item . "\n";
        }

        $this->notification()->info(
            $title = 'Created',
            $message
        );
    }

    public function render()
    {
        $dublicated_bib_numbers = [];

        if($this->event_id > 0){
            $event = Event::find($this->event_id);
            foreach ($event->photos as $photo){
                /** @var Collection $bib_numbers_groups */
                $bib_numbers_groups = $photo->bib_numbers->groupBy("bib_number");
                /** @var Collection $bib_numbers_group */
                foreach ($bib_numbers_groups as $bib_numbers_group){
                    if(!isset($dublicated_bib_numbers[$bib_numbers_group->first()->bib_number])){
                        $dublicated_bib_numbers[$bib_numbers_group->first()->bib_number] = 0;
                    }
                    $dublicated_bib_numbers[$bib_numbers_group->first()->bib_number] =  $dublicated_bib_numbers[$bib_numbers_group->first()->bib_number] + $bib_numbers_group->count();
                }
            }
            foreach ($dublicated_bib_numbers as $key => $dublicated_bib_number){
                if($dublicated_bib_number < 10){
                    unset($dublicated_bib_numbers[$key]);
                }
            }
            arsort($dublicated_bib_numbers);
        }

        return view('livewire.dashboard.analysis.manual_tagging.multiple',['dublicated_bib_numbers' => $dublicated_bib_numbers]);
    }
}

