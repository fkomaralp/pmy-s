<?php

namespace App\Actions\Dashboard\Faq;

use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class Update
{
    public function update(Faq $faq, array $input)
    {
        Validator::make($input, [
            'title' => ['required', 'string', 'max:255'],
        ])->validate();

        $faq->forceFill($input)->save();

        session()->flash('success', 'Faq Title successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.pages.faq.index");
    }
}
