<?php

namespace App\Http\Livewire\Dashboard\Events;

use App\Models\City;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Price;
use Illuminate\Support\Facades\Auth;
use App\Actions\Dashboard\Events\Create as EventCreate;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use RedirectsActions, WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];
    public $filters = [];

//    public $showDropdown = false;
//    public $showCityDropdown = false;
//
//    public $selected_country = false;
//    public $selected_city = false;
    public $selected_prices = [];

//    public $country_id;
//    public $city_id;

    public $country = "United Kingdom";
    public $city;

//    public $countries;
//    public $cities;
    public $price_list;

//    public $filterCountryByName = "";
//    public $filterCityByName = "";

    public $event_date = "";
    public $image;
    public $note;
    public $title;

    public $status = true;
    public $protected = false;
    public $sponsored = false;
    public $fit_image_to_width = false;
    public $vertical_image;
    public $horizontal_image;
    public $opacity = 1;
    public $orientation = 0;
    public $width = 50;
    public $position = 'bottom-0 left-0';

    public function mount()
    {
//        $this->selected_country = Country::all()->first();
//        $this->country_id = Country::all()->first()->id;
//        $this->selected_city = $this->selected_country->cities()->first();
//        $this->city_id = $this->selected_country->cities()->first()->id;
        $this->selected_prices = [];
        $this->price_list = Price::whereIn('type', [0,1])->get();
//        $this->opacity = Configuration::getValue("SPONSOR_OPACITY");
//        $this->vertical_image = Configuration::getValue("SPONSOR_VERTICAL_IMAGE");
//        $this->horizontal_image = Configuration::getValue("SPONSOR_HORIZONTAL_IMAGE");
//        $this->position = Configuration::getValue("SPONSOR_POSITION");
//        $this->fit_image_to_width = boolval(Configuration::getValue("FIT_IMAGE_TO_WIDTH"));
    }

    public function updatePosition($position)
    {
        $this->position = $position;
    }

    public function createEvent(EventCreate $creator)
    {
        $this->resetErrorBag();

        $creator->create(array_merge($this->state, [
            "filters" => $this->filters,
//            "country_id" => $this->country_id,
            "image" => $this->image,
//            "city_id" => $this->city_id,
            "country" => $this->country,
            "city" => $this->city,
            "event_date" => $this->event_date,
            "status" => $this->status,
            "selected_prices" => $this->selected_prices,
            "protected" => $this->protected,
            "is_sponsored" => $this->sponsored,
            "fit_image_to_width" => $this->fit_image_to_width,
            "position" => $this->position,
            "horizontal_image" => $this->horizontal_image,
            "vertical_image" => $this->vertical_image,
            "opacity" => $this->opacity,
            "title" => $this->title,
            "note" => $this->note,
        ]));

        return $this->redirectPath($creator);
    }

//    public function selectCountry($country_id)
//    {
//        $this->country_id = $country_id;
//        $this->selected_country = Country::find($country_id);
//        $this->cities = $this->selected_country->cities()->distinct("name")->get()->take(100);
//        $this->selected_city = $this->cities->first() ?? null;
//        $this->showDropdown = false;
//        $this->filterCountryByName = "";
//    }

//    public function selectCity($city_id)
//    {
//        $this->city_id = $city_id;
//        $this->selected_city = City::find($city_id);
//        $this->showCityDropdown = false;
//        $this->filterCityByName = "";
//    }

    public function selectPrice($price_id)
    {
        $price = Price::where("id", $price_id)->first()->toArray();
        $this->selected_prices[] = $price;
    }

    public function updatedSponsored($value)
    {
        if($value){
            $this->price_list = Price::all();

            $this->selected_prices = [];

        } else {
            $this->price_list = Price::whereIn('type', [0,1])->get();

            $this->selected_prices = [];

        }
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
//        if($this->filterCountryByName != ""){
//            $this->countries = Country::where("name", "like", '%'.$this->filterCountryByName.'%')->get();
//        } else {
//            $this->countries = Country::all();
//        }

//        if($this->filterCityByName != ""){
//            $this->cities = City::where("name", "like", '%'.$this->filterCityByName.'%')
//                ->where("country_id", $this->selected_country->id)
//                ->get()
//                ->unique("name")
//                ->take(50);
//        } else {
//            $this->cities = $this->selected_country
//                ->cities()
//                ->get()
//                ->unique("name")
//                ->take(50);
//        }

        return view('livewire.dashboard.events.create', [
//            "countries" => $this->countries,
//            "cities" => $this->cities
        ]);
    }

    public function back()
    {
        return redirect(url()->route("dashboard.events.index"));
    }
}
