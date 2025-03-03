<?php

namespace App\Actions\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;

class Events
{
    public function update(array $input)
    {
//        Validator::make($input, [
//            'password_protecting_status' => ['nullable'],
//            'FAVICON' => ['nullable'],
//        ])->validate();

        Configuration::updateOrCreate(["name" => "EVENTS_PASSWORD_PROTECTED"], ["value" => intval($input["EVENTS_PASSWORD_PROTECTED"])]);

        $image = $input["DEFAULT_EVENT_IMAGE"];

        $_input['imagename'] = time().'.'.$image->extension();

        $destinationPath = storage_path('app/public/event_thumbnails');

        $img = ImageManagerStatic::make($image->path());

        $img->orientate()->resize(500, null, function ($constraint) {

            $constraint->aspectRatio();

        })->save($destinationPath.'/'.$_input['imagename']);

        $input["DEFAULT_EVENT_IMAGE"] = '/storage/event_thumbnails/'.$_input['imagename'];

        Configuration::updateOrCreate(["name" => "DEFAULT_EVENT_IMAGE"], ["value" => $input["DEFAULT_EVENT_IMAGE"]]);

        session()->flash('success', 'Settings successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.settings.index");
    }
}
