<?php

namespace App\Http\Livewire\Dashboard\Analysis\ManualTagging;

use App\Jobs\ClearUnnecessaryImageFiles;
use App\Models\BibNumber;
use App\Models\Photos;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class Edit extends Component
{
    use RedirectsActions, WithFileUploads, Actions;

    public $bib_numbers;
    public $photo;
    public $state = [];
    public $bib_number_to_delete;
    public $new_bib_number;

    public function mount($bib_number_file = "")
    {
        $this->photo = Photos::where("file_name", $bib_number_file)->first();
        $this->bib_numbers = $this->photo->bib_numbers;

//        $this->state = [
//            "type" => $this->price->type,
//            "title" => $this->price->title,
//            "status" => (int)$this->price->status,
//            "price" => $this->price->price,
//        ];
    }

    public function AddNewBibNumber()
    {
        $count = $this->photo->bib_numbers->where("bib_number", $this->new_bib_number)->count();

        if($count > 0){
            $this->notification()->error(
                $title = 'Error',
                $description = 'Bib number ('.$this->new_bib_number.') is already registered.'
            );

            return false;
        }

        $this->photo->bib_numbers()->create([
            "bib_number" => $this->new_bib_number
        ]);

        $this->refreshRecords();

        $this->notification()->success(
            $title = 'Added',
            $description = 'New bib number (' . $this->new_bib_number . ') added!'
        );
    }

    public function downloadRealImage()
    {
        $storage = new StorageClient([
            'keyFilePath' => config_path("googlevisionkey.json")
        ]);

        $bucket_name = $this->photo->event->bucket_name;

        $bucket = $storage->bucket($bucket_name);
        $object = $bucket->object($this->photo->file_name);

        $path = storage_path("app/cloud-storage-tmp/edit");

        $image_path = $path . "/" . $this->photo->file_name;

        $object->downloadToFile($image_path);

        $headers = ['Content-Type: image/jpeg'];

        ClearUnnecessaryImageFiles::dispatch($image_path)
            ->delay(now()->addMinutes(3));

        return \Response::download($image_path, $this->photo->file_name, $headers);
    }

    public function deleteBibNumber($file_name, $bib_number)
    {
        $this->photo->bib_numbers->where("bib_number", $bib_number)->first()->delete();

        $this->refreshRecords();

        $this->notification()->success(
            $title = 'Deleted',
            $description = 'Bib number deleted!'
        );

//        $this->notification()->notify([
//            'title'         => 'Error',
//            'description'   => 'Error when deleting bib number. You need to add more bib number to delete current bib number! Or you can delete this record using Manual Tagging list.',
//            'icon'          => 'error',
//            'timeout'       => 30000
//        ]);

    }

    public function render()
    {
        return view('livewire.dashboard.analysis.manual_tagging.edit');
    }

    private function refreshRecords()
    {
        $this->photo = Photos::where("file_name", $this->photo->file_name)->first();
        $this->bib_numbers = $this->photo->bib_numbers;
    }
}

