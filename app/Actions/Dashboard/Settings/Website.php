<?php

namespace App\Actions\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class Website
{
    public function update(array $input)
    {
        Validator::make($input, [
            'LOGO' => ['nullable'],
            'FAVICON' => ['nullable'],
        ])->validate();

        foreach ($input as $key => $i) {
            Configuration::updateOrCreate(["name" => $key], ["value" => $i]);
        }

        if (array_key_exists("LOGO", $input)) {
            $path = $input["LOGO"]->store("public/configuration");

            $configuration = Configuration::where("name", "LOGO")->first();
            $configuration->value = "/" . str_replace("public", "storage", $path);
            $configuration->save();
        }

        if (array_key_exists("FAVICON", $input)) {
            $path = $input["FAVICON"]->store("public/configuration");

            $configuration = Configuration::where("name", "FAVICON")->first();
            $configuration->value = "/" . str_replace("public", "storage", $path);
            $configuration->save();
        }

        session()->flash('success', 'Settings successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.settings.index");
    }
}

