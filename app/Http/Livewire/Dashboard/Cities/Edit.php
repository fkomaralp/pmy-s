<?php

namespace App\Http\Livewire\Dashboard\Cities;

use App\Actions\Dashboard\Cities\Update;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Edit extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public $city;
    public $name;
    public $countries;
    public $selected_county;
    public $country_id;
    public $city_id;
    public $showDropdown = false;
    public $filterCountryByName = '';

    public function mount($city_id)
    {
        $this->city = City::find($city_id);
        $this->city_id = $city_id;
        $this->name = $this->city->name;

        $this->selected_county = $this->city->country;
    }

    public function selectCountry($country_id)
    {
        $this->country_id = $country_id;
        $this->fill(["country_id" => $this->country_id]);
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
    public function editCity(Update $creator)
    {
        $this->resetErrorBag();

        $creator->update($this->city_id, array_merge($this->state,["country_id" => $this->country_id]));

        return $this->redirectPath($creator);
    }

    public function render()
    {
        if($this->filterCountryByName != ""){
            $this->countries = Country::where("name", "like", '%'.$this->filterCountryByName.'%')->get();
        } else {
            $this->countries = Country::all();
        }

        return view('livewire.dashboard.cities.edit', ["countries" => $this->countries]);
    }
}
