<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use App\Actions\Dashboard\Settings\Events as EventSettingsUpdate;
use Livewire\WithFileUploads;

class Events extends Component
{
    use RedirectsActions, WithFileUploads;

    public $state = [];
    public $DEFAULT_EVENT_IMAGE;

    public function mount()
    {
        $PASSWORD_PROTECTED = Configuration::getValue("EVENTS_PASSWORD_PROTECTED");
        $DEFAULT_EVENT_IMAGE = Configuration::getValue("DEFAULT_EVENT_IMAGE");

        $this->state["EVENTS_PASSWORD_PROTECTED"] = boolval($PASSWORD_PROTECTED);
        $this->DEFAULT_EVENT_IMAGE = $DEFAULT_EVENT_IMAGE;
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updateEventSettings(EventSettingsUpdate $updater)
    {
        $this->resetErrorBag();

        $updater->update(array_merge($this->state, ["DEFAULT_EVENT_IMAGE" => $this->DEFAULT_EVENT_IMAGE]));

        $this->emit('saved');

//        return $this->redirectPath($updater);
    }

    public function render()
    {
        $PASSWORD_PROTECTED = Configuration::getValue("EVENTS_PASSWORD_PROTECTED");

        return view('livewire.dashboard.settings.events', [
            "EVENTS_PASSWORD_PROTECTED" => $PASSWORD_PROTECTED
        ]);
    }
}
