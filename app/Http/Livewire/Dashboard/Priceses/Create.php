<?php

namespace App\Http\Livewire\Dashboard\Priceses;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use App\Actions\Dashboard\Price\Create as PriceCreate;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public $status = true;

    public function mount()
    {
        $this->state = [
          "status" => true,
          "type" => 0,
        ];
    }

    public function createPrice(PriceCreate $creator)
    {
        $this->resetErrorBag();

        $creator->create($this->state);

        return $this->redirectPath($creator);
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }


    public function render()
    {
        return view('livewire.dashboard.priceses.create');
    }

    public function back()
    {
        return redirect(url()->route("dashboard.priceses.index"));
    }
}
