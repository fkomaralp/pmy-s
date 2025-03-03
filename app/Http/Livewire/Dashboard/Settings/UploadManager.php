<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use App\Actions\Dashboard\Settings\UploadManager as UploadManagerSettingsUpdate;
use Livewire\WithFileUploads;

class UploadManager extends Component
{
    use RedirectsActions, WithFileUploads;

    public $state = [];

    public function mount()
    {
        $MAX_PARALLEL_COUNT = Configuration::getValue("MAX_PARALLEL_COUNT");

        $this->state["MAX_PARALLEL_COUNT"] = $MAX_PARALLEL_COUNT;
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updateUploadManagerSettings(UploadManagerSettingsUpdate $updater)
    {
        $this->resetErrorBag();

        $updater->update(array_merge($this->state, []));

        $this->emit('saved');

//        return $this->redirectPath($updater);
    }

    public function render()
    {
        return view('livewire.dashboard.settings.upload_manager');
    }
}
