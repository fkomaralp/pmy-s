<?php

namespace App\Http\Livewire\Dashboard\Priceses;

use App\Models\Event;
use App\Models\Price;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Index extends Component
{
    use RedirectsActions;

    public $delete_id;

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function delete()
    {
        Price::destroy($this->delete_id);

        session()->flash('success', 'Price successfully deleted.');

        return redirect()->to(url()->previous());
    }

    public function render()
    {
        $priceses = Price::paginate(10);

        return view('livewire.dashboard.priceses.index', ["priceses" => $priceses]);
    }
}
