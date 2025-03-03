<?php

namespace App\Actions\Dashboard\Price;

use App\Models\Event;
use App\Models\Price;
use Illuminate\Support\Facades\Validator;

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
        Validator::make($input, [
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'type' => ['required', 'integer'],
        ])->validate();

        Price::create($input);

        session()->flash('success', 'Price successfully created.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.priceses.index");
    }
}
