<?php

namespace App\Http\Livewire\Dashboard\Cities;

use App\Actions\Dashboard\Cities\Update;
use App\Actions\Dashboard\Cities\Create as CityCreate;
use App\Models\Country;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Create extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public $name;
    public $countries;
    public $selected_county;
    public $country_id;
    public $showDropdown = false;
    public $filterCountryByName = '';

    public function mount()
    {
        $this->selected_county = Country::all()->first();
        $this->name = "";
    }

    public function selectCountry($country_id)
    {
        $this->country_id = $country_id;
        $this->selected_county = Country::find($country_id);

        $this->showDropdown = false;
        $this->filterCountryByName = "";
    }

    /**
     * Create a new team.
     *
     * @param Update $creator
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createCity(CityCreate $creator)
    {
        $this->resetErrorBag();

        $creator->create(array_merge($this->state,["country_id" => $this->country_id]));

        return $this->redirectPath($creator);
    }

    public function render()
    {
        if($this->filterCountryByName != ""){
            $this->countries = Country::where("name", "like", '%'.$this->filterCountryByName.'%')->get();
        } else {
            $this->countries = Country::all();
        }

        return view('livewire.dashboard.cities.create', ["countries" => $this->countries]);
    }
}
