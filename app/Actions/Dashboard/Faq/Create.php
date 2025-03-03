<?php

namespace App\Actions\Dashboard\Faq;

use App\Models\Faq;
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
        ])->validate();

        Faq::create($input);

        session()->flash('success', 'Faq Title successfully created.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.pages.faq.index");
    }
}
