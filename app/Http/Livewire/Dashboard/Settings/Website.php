<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use App\Actions\Dashboard\Settings\Website as WebsiteSettingsUpdate;
use Livewire\WithFileUploads;

class Website extends Component
{
    use RedirectsActions, WithFileUploads;

    public $state = [];

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updateSettings(WebsiteSettingsUpdate $updater)
    {
        $this->resetErrorBag();

        $updater->update(array_merge($this->state, []));

        $this->emit('saved');

//        return $this->redirectPath($updater);
    }

    public function render()
    {
        $logo = Configuration::getValue("LOGO");
        $favicon = Configuration::getValue("FAVICON");
        $this->state["TITLE"] = Configuration::getValue("TITLE");
        $this->state["EMAIL"] = Configuration::getValue("EMAIL");

        return view('livewire.dashboard.settings.website', [
            "LOGO" => $logo,
            "FAVICON" => $favicon
        ]);
    }
}
