<?php

namespace App\Actions\Dashboard\Events;

use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;

class Update extends Component
{
    public function update($event_id, array $input)
    {
        $event = Event::find($event_id);

        $validate = [
            'title' => ['required', 'string', 'max:255'],
//            'image' => ['required'],
//            'country_id' => ['required'],
//            'city_id' => ['required'],
            'country' => ['required'],
            'city' => ['required'],
            'event_date' => ['required'],
//            'note' => ['required'],
            'selected_prices' => ['required']
        ];

        if($input["is_sponsored"]){
            $is_sponsored_validate = [
                'fit_image_to_width' => ['required'],
                'position' => ['required'],
                'horizontal_image' => ['required'],
                'vertical_image' => ['required'],
                'opacity' => ['required'],
            ];

            $validate = array_merge($is_sponsored_validate, $validate);
        }

        Validator::make($input, $validate)->validate();

        $input["status"] = intval($input["status"]);
        $input["protected"] = intval($input["protected"]);

        if(array_key_exists("image", $input) && $input["image"] !== null){

//            $path = $input["image"]->store('event_thumbnails');
//            $input["image"] = $path;

            $image = $input["image"];

            $_input['imagename'] = time().'.'.$image->extension();

            $destinationPath = storage_path('app/public/event_thumbnails');

            $img = ImageManagerStatic::make($image->path());

            $img->orientate()->resize(500, null, function ($constraint) {

                $constraint->aspectRatio();

            })->save($destinationPath.'/'.$_input['imagename']);

            $input["image"] = '/storage/event_thumbnails/'.$_input['imagename'];
        } else {
            $input["image"] = $event->image;
        }

        if(is_a($input["horizontal_image"], TemporaryUploadedFile::class)){
            $path = $input["horizontal_image"]->store("public");
            $SPONSOR_HORIZONTAL_IMAGE = "/".str_replace("public", "storage", $path);
            $input["horizontal_image"] = $SPONSOR_HORIZONTAL_IMAGE;
        }
        if(is_a($input["vertical_image"], TemporaryUploadedFile::class)){
            $path = $input["vertical_image"]->store("public");
            $SPONSOR_VERTICAL_IMAGE = "/".str_replace("public", "storage", $path);
            $input["vertical_image"] = $SPONSOR_VERTICAL_IMAGE;
        }

        $selected_prices = $input["selected_prices"];
        unset($input["selected_prices"]);

        $input["is_sponsored"] = intval($input["is_sponsored"]);
        $input["sponsor_fit_image_to_width"] = intval($input["fit_image_to_width"]);
        unset($input["fit_image_to_width"]);
        $input["sponsor_position"] = $input["position"];
        unset($input["position"]);
        $input["sponsor_horizontal_image"] = $input["horizontal_image"];
        unset($input["horizontal_image"]);
        $input["sponsor_vertical_image"] = $input["vertical_image"];
        unset($input["vertical_image"]);
        $input["sponsor_opacity"] = $input["opacity"];
        unset($input["opacity"]);

        $event->price_list()->delete();
        $event->filters()->delete();

        foreach($selected_prices as $price){
            $event->price_list()->create([
                "price_id" => (int)$price,
            ]);
        }

        foreach($input["filters"] as $filter){
            if((int)$filter["from"] > 0 || (int)$filter["to"] > 0){
                $event->filters()->create([
                    "filter_from" => $filter["from"],
                    "filter_to" => $filter["to"],
                ]);
            }
        }
        unset($input["filters"]);

        $event->forceFill($input)->save();

        session()->flash('success', 'Event successfully updated.');

    }

    public function redirectTo()
    {
        return url()->route("dashboard.events.index");
    }
}
