<?php

namespace App\Http\Livewire\Dashboard\Cities;

use App\Models\City;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use RedirectsActions,WithPagination;

    public $delete_id;

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function delete()
    {
        City::destroy($this->delete_id);

        session()->flash('success', 'City successfully deleted.');

        return redirect()->to(url()->previous());
    }

    public function render()
    {
        return view('livewire.dashboard.cities.index', [
            "cities" => City::paginate(10)
        ]);
    }
}
