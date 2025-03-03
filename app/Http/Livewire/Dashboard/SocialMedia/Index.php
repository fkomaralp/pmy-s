<?php

namespace App\Http\Livewire\Dashboard\SocialMedia;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use App\Actions\Dashboard\SocialMedia\Update as SocialMediaUpdate;
use Livewire\WithFileUploads;

class Index extends Component
{
    use RedirectsActions;

    public $state = [];

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updateConfiguration(SocialMediaUpdate $updater)
    {
        $this->resetErrorBag();

        $updater->update(array_merge($this->state, []));

        return $this->redirectPath($updater);
    }

    public function render()
    {
        $this->state["LINKEDIN"] = Configuration::getValue("LINKEDIN");
        $this->state["TWITTER"] = Configuration::getValue("TWITTER");
        $this->state["FACEBOOK"] = Configuration::getValue("FACEBOOK");
        $this->state["INSTAGRAM"] = Configuration::getValue("INSTAGRAM");

        return view('livewire.dashboard.social_media.index');
    }
}
