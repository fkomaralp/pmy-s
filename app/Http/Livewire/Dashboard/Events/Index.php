<?php

namespace App\Http\Livewire\Dashboard\Events;

use App\Models\Event;
use Google\Cloud\Storage\StorageClient;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Index extends Component
{
    use RedirectsActions;

    public $search;

    public function delete($id)
    {
        $storage = new StorageClient([
            "keyFile" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        $event = Event::find($id);

        try {

            $bucket = $storage->bucket($event->bucket_name);
            $objects = $bucket->objects();

            foreach ($objects as $object) {
                $object->delete();
            }

            $bucket->delete();

        } catch (\Exception $e){

        }

        $event->bib_numbers()->delete();
        $event->filters()->delete();

        Event::destroy($id);

        session()->flash('success', 'Event successfully deleted.');

        return redirect()->to(url()->previous());
    }

    public function render()
    {
        $events = Event::
        where("title", "LIKE", "%".$this->search."%")
        ->orWhere("country", "LIKE", "%".$this->search."%")
        ->orWhere("city", "LIKE", "%".$this->search."%")
        ->orWhere("event_date", "LIKE", "%".$this->search."%")

            ->paginate(10);

        return view('livewire.dashboard.events.index', ["events" => $events]);
    }
}
