<?php

namespace App\Http\Livewire\Dashboard\Countries;

use App\Models\Country;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use RedirectsActions,WithPagination;

    public function render()
    {
        return view('livewire.dashboard.countries.index', [
            "countries" => Country::paginate(10)
        ]);
    }
}
