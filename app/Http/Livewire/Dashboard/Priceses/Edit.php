<?php

namespace App\Http\Livewire\Dashboard\Priceses;

use App\Actions\Dashboard\Price\Update as PriceUpdate;
use App\Models\City;
use App\Models\Country;
use App\Models\Price;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];
    /**
     * @var mixed
     */
    public $price;

    public function mount($price_id)
    {
        $this->price = Price::find($price_id);

        $this->state = [
            "type" => $this->price->type,
            "title" => $this->price->title,
            "status" => (int)$this->price->status,
            "price" => $this->price->price,
        ];
    }

    /**
     * Create a new team.
     *
     * @param PriceUpdate $creator
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updatePrice(PriceUpdate $creator)
    {
        $this->resetErrorBag();

        $creator->update($this->price, $this->state);

        return $this->redirectPath($creator);
    }

    public function render()
    {
        return view('livewire.dashboard.priceses.edit');
    }

    public function back()
    {
        return redirect(url()->route("dashboard.priceses.index"));
    }
}
