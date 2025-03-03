<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use App\Actions\Dashboard\Settings\ImageAnalysis as ImageAnalysisSettingsUpdate;
use Livewire\WithFileUploads;

class ImageAnalysis extends Component
{
    use RedirectsActions;

    public $state = [];

    public function mount()
    {
        $IMAGE_ANALYSIS_MAX_PARALLEL_COUNT = Configuration::getValue("IMAGE_ANALYSIS_MAX_PARALLEL_COUNT");

        $this->state["IMAGE_ANALYSIS_MAX_PARALLEL_COUNT"] = $IMAGE_ANALYSIS_MAX_PARALLEL_COUNT;
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updateSettings(ImageAnalysisSettingsUpdate $updater)
    {
        $this->resetErrorBag();

        $updater->update(array_merge($this->state, []));

        $this->emit('saved');

//        return $this->redirectPath($updater);
    }

    public function render()
    {

        return view('livewire.dashboard.settings.image_analysis');
    }
}
