<?php

namespace App\Actions\Dashboard\Price;

use App\Models\Price;
use Illuminate\Support\Facades\Validator;

class Update
{
    public function update(Price $price, array $input)
    {
        Validator::make($input, [
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'type' => ['required', 'integer'],
        ])->validate();

        $price->forceFill($input)->save();

        session()->flash('success', 'Price successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.priceses.index");
    }
}
