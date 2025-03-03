<?php

namespace App\Http\Livewire\Dashboard\Events;

use App\Actions\Dashboard\Events\Update;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Event;
use App\Models\Price;
use Illuminate\Notifications\Notifiable;
use Intervention\Image\ImageManagerStatic;
use Laravel\Jetstream\RedirectsActions;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class Edit extends Update
{
    use RedirectsActions, WithFileUploads, Actions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];
    public $filters = [];

    public $event;

//    public $showDropdown = false;
//    public $showCityDropdown = false;
//
//    public $selected_country = false;
//    public $selected_city = false;
    public $selected_prices = [];

//    public $country_id;
//    public $city_id;

//    public $countries;
//    public $cities;
    public $price_list = [];

//    public $filterCountryByName = "";
//    public $filterCityByName = "";

    public $event_date = "";

    public $country;
    public $city;

    public $event_id;
    public $image;

    public $status = false;
    public $protected = false;
    public $sponsored = false;
    public $fit_image_to_width = false;
    public $vertical_image;
    public $horizontal_image;
    public $opacity = 1;
    public $orientation = 0;
    public $position = 'bottom-0 right-0';

    public function mount($event_id)
    {
        $this->event = Event::find($event_id);
        $this->event_id = $event_id;
        $this->country = $this->event->country;
        $this->city = $this->event->city;
//        $this->city_id = $this->event->city_id;
//        $this->country_id = $this->event->country_id;
        $this->event_date = $this->event->event_date;

        $this->state["title"] = $this->event->title;
        $this->state["note"] = $this->event->note;

//        $this->selected_country = $this->event->country;
//        $this->selected_city = $this->event->city;
        $this->status = $this->event->status;
        $this->protected = $this->event->protected;

        if($this->event->is_sponsored){
            $price_list_ids = [0,1,2];
        } else {
            $price_list_ids = [0,1];
        }

        $this->price_list = Price::whereIn('type', $price_list_ids)->get()->toArray();

        $this->selected_prices = $this->event->price_list()->get()->pluck("price_id")->toArray();

        $filters = [];

        foreach ($this->event->filters()->get() as $key => $filter){
            $filters[$key]["from"] = $filter->filter_from;
            $filters[$key]["to"] = $filter->filter_to;
        }
        $this->filters = $filters;

        $this->sponsored = $this->event->is_sponsored;
        $this->fit_image_to_width = $this->event->sponsor_fit_image_to_width;
        $this->vertical_image = $this->event->sponsor_vertical_image;
        $this->horizontal_image = $this->event->sponsor_horizontal_image;
        $this->opacity = $this->event->sponsor_opacity;
        $this->position = $this->event->sponsor_position;
    }

    public function updatePosition($position)
    {
        $this->position = $position;
    }

    public function updatedSponsored($value)
    {
        if($value){
            $this->price_list = Price::all();

            $this->selected_prices = [];

            $this->selected_prices = $this->event->price_list()->get()->pluck("price_id")->toArray();

            $price = Price::where('type', 2)->first();

            $this->selected_prices[] = $price->id;

        } else {
            $this->price_list = Price::all();

            $this->selected_prices = [];

            $this->selected_prices = $this->event->price_list()->get()->pluck("price_id")->toArray();

        }
    }

    public function downloadSampleHorizontal()
    {
        $get_from_file_upload = is_a($this->horizontal_image, TemporaryUploadedFile::class);

        $image_path = public_path("/img/vertical_sample.jpg");

        $image_path = $this->renderImage($image_path, $get_from_file_upload);

        $headers = ['Content-Type: image/jpeg'];

        if(file_exists($image_path)){
            return \Response::download($image_path, "vertical_sample.jpg", $headers);
        }

        return \Response::download($image_path, "vertical_sample.jpg", $headers);
    }

    public function downloadSampleVertical()
    {
        $get_from_file_upload = is_a($this->vertical_image, TemporaryUploadedFile::class);

        $image_path = public_path("/img/horizontal_sample.jpg");

        $image_path = $this->renderImage($image_path, $get_from_file_upload);

        if($image_path !== false){
            $headers = ['Content-Type: image/jpeg'];

            if(file_exists($image_path)){
                return \Response::download($image_path, "horizontal_sample.jpg", $headers);
            }

            return \Response::download($image_path, "horizontal_sample.jpg", $headers);
        }
    }

//    public function selectCountry($country_id)
//    {
//        $this->country_id = $country_id;
//
//        $this->selected_country = Country::find($country_id);
//
//        $this->showDropdown = false;
//        $this->filterCountryByName = "";
//    }
//
//    public function selectCity($city_id)
//    {
//        $this->city_id = $city_id;
//        $this->selected_city = City::find($city_id);
//        $this->showCityDropdown = false;
//        $this->filterCityByName = "";
//    }

    /**
     * Create a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateEvent()
    {
        $this->resetErrorBag();

        $this->update($this->event_id, array_merge($this->state, [
//            "country_id" => $this->country_id,
            "filters" => $this->filters,
            "image" => $this->image,
            "country" => $this->country,
            "city" => $this->city,
//            "city_id" => $this->city_id,
            "event_date" => $this->event_date,
            "status" => $this->status,
            "protected" => $this->protected,
            "selected_prices" => $this->selected_prices,
            "is_sponsored" => $this->sponsored,
            "fit_image_to_width" => $this->fit_image_to_width,
            "position" => $this->position,
            "horizontal_image" => $this->horizontal_image,
            "vertical_image" => $this->vertical_image,
            "opacity" => $this->opacity,
        ]));

        return $this->redirectPath($this);
    }

    public function back()
    {
        return redirect(url()->route("dashboard.events.index"));
    }

    public function render()
    {
//        if($this->filterCountryByName != ""){
//            $this->countries = Country::where("name", "like", '%'.$this->filterCountryByName.'%')->get();
//        } else {
//            $this->countries = Country::all();
//        }
//
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

        return view('livewire.dashboard.events.edit', [
//            "countries" => $this->countries,
//            "cities" => $this->cities
        ]);
    }

    private function renderImage($raw_image, $get_from_file_upload)
    {
        $SPONSOR_POSITION = Configuration::getValue("SPONSOR_POSITION");

        switch($SPONSOR_POSITION){
            case 'top-0 left-0':
                $position = 'top-left';
                break;
            case 'inset-0 mx-auto':
                $position = 'top';
                break;
            case 'top-0 right-0':
                $position = 'top-right';
                break;
            case 'inset-y-0 left-0 my-auto':
                $position = 'left';
                break;
            case 'inset-y-0 my-auto right-0':
                $position = 'right';
                break;
            case 'bottom-0 left-0':
                $position = 'bottom-left';
                break;
            case 'bottom-0 inset-x-0 mx-auto':
                $position = 'bottom';
                break;
            case 'bottom-0 right-0':
                $position = 'bottom-right';
                break;

            default:
                $position = 'center';
                break;
        }

        if($get_from_file_upload){
            /** @var TemporaryUploadedFile $vertical_image */
            $vertical_image = $this->vertical_image;
            /** @var TemporaryUploadedFile $horizontal_image */
            $horizontal_image = $this->horizontal_image;
            $sponsor_horizontal_image = $horizontal_image->get();
            $sponsor_vertical_image = $vertical_image->get();
        } else {
            $sponsor_horizontal_image = public_path($this->event->sponsor_horizontal_image);
            $sponsor_vertical_image = public_path($this->event->sponsor_vertical_image);
        }
        $img = ImageManagerStatic::make($raw_image);

        $FIT_IMAGE_TO_WIDTH = Configuration::getValue("FIT_IMAGE_TO_WIDTH");

        $sponsored_image = "";

        if($img->getWidth() >= $img->getHeight()){
            $sponsored_image = $sponsor_horizontal_image;
        }

        if($img->getWidth() < $img->getHeight()){
            $sponsored_image = $sponsor_vertical_image;
        }

        if(!file_exists($sponsored_image)){
            $this->notification()->error(
                $title = 'Error',
                $description = 'Sponsored image not found. Please add new sponsored image and save this event. After that you can preview your sponsored settings'
            );
            return false;
        }

        $sponsor_watermark_image_path = $sponsored_image;
        $sponsor_watermark_image = ImageManagerStatic::make($sponsor_watermark_image_path);

//        $sponsor_width = (int)Configuration::getValue("SPONSOR_WIDTH");

        if($FIT_IMAGE_TO_WIDTH){
            $sponsor_width = $img->width();
            $sponsor_watermark_image->resize($sponsor_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $sponsor_watermark_image->opacity(floatval(Configuration::getValue("SPONSOR_OPACITY")));
        $sponsor_watermark_image->rotate((int)Configuration::getValue("SPONSOR_ORIENTATION"));

        $result_file_path = public_path("/img/rendered_sample.jpg");

        $img->orientate()->insert($sponsor_watermark_image, $position, 100);
        $img->save($result_file_path, 99);

        return $result_file_path;
    }
}
