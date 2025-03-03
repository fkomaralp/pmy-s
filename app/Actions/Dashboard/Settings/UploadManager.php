<?php

namespace App\Actions\Dashboard\Settings;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class UploadManager
{
    public function update(array $input)
    {
        Validator::make($input, [
            'MAX_PARALLEL_COUNT' => ['required'],
        ])->validate();

        foreach($input as $key => $i) {
            Configuration::updateOrCreate(["name" => $key], ["value" => $i]);
        }

        session()->flash('success', 'Settings successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.settings.index");
    }
}
