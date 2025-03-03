<?php

namespace App\Http\Livewire\Dashboard\Analysis\Settings;

use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Watermark extends Component
{
    use RedirectsActions;

    public function render()
    {
        return view('livewire.dashboard.analysis.settings.watermark');
    }
}
