<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use RedirectsActions;

    public $state = [];

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function render()
    {
        return view('livewire.dashboard.settings.index');
    }
}
