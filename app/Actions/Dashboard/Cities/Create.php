<?php

namespace App\Actions\Dashboard\Cities;

use App\Models\City;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class Create
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param $city_id
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required'],
        ])->validate();

        City::create($input);

        session()->flash('success', 'City successfully created.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.locations.cities.index");
    }
}
