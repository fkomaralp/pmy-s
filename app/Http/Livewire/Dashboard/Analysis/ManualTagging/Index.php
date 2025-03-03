<?php

namespace App\Http\Livewire\Dashboard\Analysis\ManualTagging;

use App\Classes\PaginationHelper;
use App\Classes\UpdateBibNumberJsonToCloud;
use App\Jobs\ClearUnnecessaryImageFiles;
use App\Jobs\ImageReAnalysisJob;
use App\Models\BibNumber;
use App\Models\Configuration;
use App\Models\Event;
use App\Models\Photos;
use App\Models\UploadManagerImages;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Bus;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class Index extends Component
{
    use RedirectsActions, WithFileUploads, WithPagination, Actions;

    protected $listeners = ["refreshPage" => "render"];

    public $event_id = 0;
    public $show_records = 10;
    public $search = "";
    public $searchFileName = "";
    public $new_bib_number;
    public $showAllUntagged = false;
    public $analysisStarted = false;
    public $counter = "0 / 0";
    /** @var array */
    public $image_bag;
    /** @var array */
    public $loading;

    public function mount($value = 0)
    {
        if($value === 0){
            $this->event_id = 0;
        } else {
            $this->event_id = $value;
        }

        $this->image_bag = [];
        $this->loading = [];
    }

    public function updatingEventId($value)
    {
        $this->event_id = $value;
        $this->resetPage();
        $this->search = "";
        $this->searchFileName = "";

        $this->dispatchBrowserEvent("update_blowup");
        $this->dispatchBrowserEvent("update_counter");
    }

    public function updatingShowAllUntagged($value)
    {
        $this->resetPage();
        $this->search = "";
        $this->searchFileName = "";

        $this->dispatchBrowserEvent("update_blowup");
        $this->dispatchBrowserEvent("update_counter");
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->dispatchBrowserEvent("update_blowup");
    }

    public function updatingSearchFileName()
    {
        $this->resetPage();
        $this->dispatchBrowserEvent("update_blowup");
    }

    public function clearSearch()
    {
        $this->resetPage();
        $this->search = "";
        $this->searchFileName = "";
        $this->dispatchBrowserEvent("update_blowup");
    }

    public function downloadRealImage($photo_id)
    {
        $photo = Photos::find($photo_id);

        $storage = new StorageClient([
            'keyFilePath' => config_path("googlevisionkey.json")
        ]);

        $bucket_name = $photo->event->bucket_name;

        $bucket = $storage->bucket($bucket_name);
        $object = $bucket->object($photo->file_name);

        $path = storage_path("app/cloud-storage-tmp/edit");

        $image_path = $path . "/" . $photo->file_name;

        $headers = ['Content-Type: image/jpeg'];

        if(file_exists($image_path)){
            return \Response::download($image_path, $photo->file_name, $headers);
        }

        $object->downloadToFile($image_path);

        ClearUnnecessaryImageFiles::dispatch($image_path)
            ->delay(now()->addMinutes(30));

        return \Response::download($image_path, $photo->file_name, $headers);
    }

    public function AddNewBibNumber($photo_id)
    {
        $photo = Photos::find($photo_id);

        $bnumbers = [];

        $new_bib_number = explode(",", $this->new_bib_number);

        if(is_string($new_bib_number)){
            $bnumbers[] = $new_bib_number;
        } else {
            $bnumbers = $new_bib_number;
        }

        foreach ($bnumbers as $bnumber){
            $count = $photo->bib_numbers->where("bib_number", $bnumber)->count();

            if($count > 0){
                $this->notification()->error(
                    $title = 'Error',
                    $description = 'Bib number ('.$bnumber.') is already registered.'
                );
            } else {
                $photo->bib_numbers()->create([
                    "bib_number" => $bnumber
                ]);

                $this->notification()->success(
                    $title = 'Added',
                    $description = 'New bib number (' . $bnumber . ') added!'
                );
            }
        }

        $this->new_bib_number = "";

        UpdateBibNumberJsonToCloud::update($photo->event);

    }

    public function deleteBibNumber($photo_id, $bib_number)
    {
        $photo = Photos::find($photo_id);

//        $photo->bib_numbers->where("bib_number", $bib_number)->first()->delete();

        $bib_numbers = BibNumber::where("bib_number", $bib_number)->get();
        foreach($bib_numbers as $bib_number){
            if($bib_number->photo){
                if((int)$photo_id === $bib_number->photo->id){
                    $bib_number->delete();
                }
            }
        }

        UpdateBibNumberJsonToCloud::update($photo->event);

        $this->notification()->success(
            $title = 'Deleted',
            $description = 'Tag deleted!'
        );

    }

    public function deletePhoto($photo_id)
    {
        $photo = Photos::find($photo_id);

        $photo->bib_numbers()->each(function($bib_number) {
            $bib_number->delete(); // <-- direct deletion
        });

        $storage = new StorageClient([
            "keyFile" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        try {

            $bucket = $storage->bucket($photo->event->bucket_name);
            $object = $bucket->object($photo->file_name);
            if($object->exists()){
                $object->delete();
            }

            @unlink(public_path($photo->thumbnail));

            $photo->delete();

            UpdateBibNumberJsonToCloud::update($photo->event);

            $this->dispatchBrowserEvent("update_blowup");

        } catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        $this->notification()->success(
            $title = 'Deleted',
            $description = 'Photo deleted'
        );
    }

    public function tagUntaggedImages()
    {
//        $this->analysisStarted = true;

//        Configuration::updateOrCreate(["name" => "ANALYSING_MANUAL_TAGGING_STATUS"], ["value" => 1]);

        $photos = Photos::where("event_id", $this->event_id)->get();
//        foreach($photos as $photo){
//            if($photo->bib_numbers->count() <= 0){
//
//                $image = UploadManagerImages::
//                where("file_name", $photo->file_name)
//                ->where("event_id", $photo->event_id)->first();
//
//                if($image){
//
//                    $image->status = 0;
//                    $image->file_name = $photo->file_name;
//                    $image->event_id = $photo->event_id;
//                    $image->path = $photo->thumbnail;
//
//                    $image->save();
//
//                } else {
//                    $image = UploadManagerImages::create([
//                        "status" => 0,
//                        "file_name" => $photo->file_name,
//                        "event_id" => $photo->event_id,
//                        "path" => $photo->thumbnail
//                    ]);
//                }
//
//                ImagesStatusUpdater::progressing($image);
//
//                ImageReAnalysisJob::dispatch($image);
//            }
//        }

        $jobs = [];

        foreach($photos as $photo){

            $image = UploadManagerImages::
            where("file_name", $photo->file_name)
                ->where("event_id", $photo->event_id)->first();

            if($image){

                $image->status = 0;
                $image->file_name = $photo->file_name;
                $image->event_id = $photo->event_id;
                $image->path = $photo->thumbnail;

                $image->save();

            } else {
                $image = UploadManagerImages::create([
                    "status" => 0,
                    "file_name" => $photo->file_name,
                    "event_id" => $photo->event_id,
                    "path" => $photo->thumbnail
                ]);
            }

            $jobs[] = new ImageReAnalysisJob($image);
        }

        $batch = Bus::
        batch($jobs)
            ->then(function (Batch $batch) {

            })
            ->name('Prepare bib number image files')
            ->dispatch();
    }

    public function prevImage($event_id, $file_name)
    {
        $original_file_name = $file_name;

        $prevs_old_file_name = null;

        if(array_key_exists($original_file_name, $this->image_bag)){
            $old_photos = $this->image_bag[$original_file_name];

            if($old_photos["file_name"] !== null){
                $prevs_old_file_name = $old_photos["file_name"];
            }
        }

//        $event_id = Photos::where("file_name", $original_file_name)->first()->event_id;

        $ordered_photos = Photos::where("event_id", $event_id)->orderByDesc("file_name")->get();

        $current_photo = $ordered_photos->where("file_name", ($prevs_old_file_name !== null) ? $prevs_old_file_name : $original_file_name)->first();

        $found = false;
        $next = null;

        foreach($ordered_photos as $photo){

            if($found){
                $next = $photo;
                break;
            }

            if($current_photo->id == $photo->id){
                $found = true;
            }
        }

        if(!$next || $next === null){
            $this->notification()->error(
                $title = 'Error',
                $description = 'Previous image not found.'
            );
            return;
        }

        $this->image_bag[$original_file_name] = [
            "file_name" => $next->file_name,
            "image" => $next->thumbnail
        ];

        $this->dispatchBrowserEvent("update_blowup");
    }

    public function nextImage($event_id, $file_name)
    {
        $original_file_name = $file_name;

        $nexts_old_file_name = null;

        if(array_key_exists($original_file_name, $this->image_bag)){
            $old_photos = $this->image_bag[$original_file_name];

            if($old_photos["file_name"] !== null){
                $nexts_old_file_name = $old_photos["file_name"];
            }
        }

//        $event_id = Photos::where("file_name", $original_file_name)->first()->event_id;

        $ordered_photos = Photos::where("event_id", $event_id)->orderBy("file_name")->get();

        $current_photo = $ordered_photos->where("file_name", ($nexts_old_file_name !== null) ? $nexts_old_file_name : $original_file_name)->first();

        $found = false;
        $next = null;

        foreach($ordered_photos as $photo){

            if($found){
                $next = $photo;
                break;
            }

            if($current_photo->id == $photo->id){
                $found = true;
            }
        }

        if(!$next || $next === null){
            $this->notification()->error(
                $title = 'Error',
                $description = 'Next image not found.'
            );
            return;
        }

        $this->image_bag[$original_file_name] = [
            "file_name" => $next->file_name,
            "image" => $next->thumbnail
        ];

        $this->dispatchBrowserEvent("update_blowup");
    }

    public function returnToRealImage($photo_id)
    {
        $original_photo_id = $photo_id;

        $photo = Photos::find($original_photo_id);

        $this->image_bag[$photo->file_name] = [
            "file_name" => $photo->file_name,
            "image" => $photo->thumbnail
            ];

        $this->dispatchBrowserEvent("update_blowup");
    }

    public function render()
    {
        $events = Event::all();

        $photos = Photos::with("bib_numbers")->where("event_id", (int)$this->event_id)->get();

        if($this->search !== ""){
            $photos = $photos->filter(function($val, $key){
                    if($val->bib_numbers->where('bib_number', '=', $this->search)->count() > 0){
                        return $val;
                    }
                    return false;
            });
        }

        if($this->searchFileName !== ""){
            $photos = $photos->filter(function($val, $key){
                    if(strpos($val->file_name, $this->searchFileName) !== false){
                        return $val;
                    }
                    return false;
            });
        }

        $photos = $photos->sortBy("file_name");

        if($this->showAllUntagged){
            $photos = $photos->filter(function($photo){
                return $photo->bib_numbers->count() === 0;
            });
        }

        $this->counter = "0 / " . $photos->filter(function($photo){
            return $photo->bib_numbers->count() === 0;
        })->count();

        $photos = PaginationHelper::paginate($photos, $this->show_records);

        $ANALYSING_MANUAL_TAGGING_STATUS = Configuration::getValue("ANALYSING_MANUAL_TAGGING_STATUS");

        return view('livewire.dashboard.analysis.manual_tagging.index', ["events" => $events, "photos" => $photos, "ANALYSING_MANUAL_TAGGING_STATUS" => $ANALYSING_MANUAL_TAGGING_STATUS]);
    }

    public function setPage($page, $pageName = 'page')
    {
        if (is_numeric($page)){
            $page = (int)$page;
            $page = $page <= 0 ? 1 : $page ;
        }
        $beforePaginatorMethod = 'updatingPaginators';
        $afterPaginatorMethod = 'updatedPaginators';

        $beforeMethod = 'updating' . $pageName;
        $afterMethod = 'updated' . $pageName;

        if (method_exists($this, $beforePaginatorMethod)) {
            $this->{$beforePaginatorMethod}($page, $pageName);
        }

        if (method_exists($this, $beforeMethod)) {
            $this->{$beforeMethod}($page, null);
        }

        $this->paginators[$pageName] =  $page;

        $this->{$pageName} = $page;

        if (method_exists($this, $afterPaginatorMethod)) {
            $this->{$afterPaginatorMethod}($page, $pageName);
        }

        if (method_exists($this, $afterMethod)) {
            $this->{$afterMethod}($page, null);
        }

        $this->dispatchBrowserEvent("update_blowup");
    }
}

