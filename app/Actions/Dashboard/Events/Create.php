<?php

namespace App\Actions\Dashboard\Events;

use App\Models\City;
use App\Models\Configuration;
use App\Models\Event;
use App\Models\EventPrice;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;
use Google\Cloud\Storage\StorageClient;
use Livewire\TemporaryUploadedFile;

class Create
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        $validate = [
            'title' => ['required', 'string', 'max:255', 'unique:events,title'],
            'image' => ['nullable'],
//            'country_id' => ['required'],
//            'city_id' => ['required'],
            'country' => ['required'],
            'city' => ['nullable'],
            'event_date' => ['required'],
            'note' => ['nullable'],
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

        $storage = new StorageClient([
            "keyFile" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
        ]);

        try {

            $title = str_replace(" ", "_", strtolower($input["title"]));

            $input["bucket_name"] = $title;

            $storage->createBucket($title);

        } catch (\Exception $e){
            throw ValidationException::withMessages(['title' => "Google Cloud : " . json_decode($e->getMessage())->error->message]);
        }

        $image = null;
        if(array_key_exists("image", $input) && $input["image"] !== null){
//            Validator::make($input, [
//                'image' => ['required'],
//            ])->validate();

            $image = $input["image"];

            $_input['imagename'] = time().'.'.$image->extension();

            $destinationPath = storage_path('app/public/event_thumbnails');

            $img = ImageManagerStatic::make($image->path());

            $img->orientate()->resize(500, null, function ($constraint) {

                $constraint->aspectRatio();

            })->save($destinationPath.'/'.$_input['imagename']);

            $image = '/storage/event_thumbnails/'.$_input['imagename'];

        }

        $input["image"] = $image;

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

        $input["event_code"] = $this->getNewRandomCode();
        $input["status"] = intval($input["status"]);
        $input["protected"] = intval($input["protected"]);
        $input["is_sponsored"] = intval($input["is_sponsored"]);
        $input["sponsor_fit_image_to_width"] = intval($input["fit_image_to_width"]);
        $input["sponsor_position"] = $input["position"];
        $input["sponsor_horizontal_image"] = $input["horizontal_image"];
        $input["sponsor_vertical_image"] = $input["vertical_image"];
        $input["sponsor_opacity"] = $input["opacity"];

        $event = Event::create($input);

        foreach($input["selected_prices"] as $price){
            $event->price_list()->create([
                "price_id" => (int)$price,
            ]);
        }

        foreach($input["filters"] as $filter){
            if($filter["from"] > 0 && $filter["to"] > 0){
                $event->filters()->create([
                    "filter_from" => $filter["from"],
                    "filter_to" => $filter["to"],
                ]);
            }
        }

        session()->flash('success', 'Event successfully created.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.events.index");
    }

    public function getNewRandomCode()
    {
        $new = strtoupper(\Str::random(8));

        $result = Event::where("event_code", $new)->count();

        if($result > 0){
            return $this->getNewRandomCode();
        }

        $new = substr($new, 0, 4) . "-" . substr($new, 4, 8);

        return $new;
    }
}
