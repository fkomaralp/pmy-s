<?php

namespace App\Actions\Dashboard\SocialMedia;

use App\Models\Configuration;
use Illuminate\Support\Facades\Validator;

class Update
{
    public function update(array $input)
    {
        Validator::make($input, [
            'LINKEDIN' => ['nullable'],
            'TWITTER' => ['nullable'],
            'FACEBOOK' => ['nullable'],
            'INSTAGRAM' => ['nullable'],
        ])->validate();

        foreach($input as $key => $i) {
            Configuration::updateOrCreate(["name" => $key], ["value" => $i]);
        }

        session()->flash('success', 'Social media links successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.settings.social_media.index");
    }
}
