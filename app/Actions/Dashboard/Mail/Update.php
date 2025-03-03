<?php

namespace App\Actions\Dashboard\Mail;

use App\Models\Configuration;
use Illuminate\Support\Facades\Validator;

class Update
{
    public function update(array $input)
    {
        Validator::make($input, [
            'host' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
            'port' => ['required'],
            'template' => ['required'],
        ])->validate();

        foreach($input as $key => $i) {
            Configuration::updateOrCreate(["name" => $key], ["value" => $i]);
        }

        session()->flash('success', 'Mail settings successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.settings.mail.index");
    }
}
