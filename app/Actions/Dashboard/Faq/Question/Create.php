<?php

namespace App\Actions\Dashboard\Faq\Question;

use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class Create
{
    public $faq;

    /**
     * Validate and create a new team for the given user.
     *
     * @param Faq $faq
     * @param array $input
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Faq $faq, array $input)
    {

        $this->faq = $faq;
        Validator::make($input, [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
        ])->validate();

        $faq->questions()->create($input);

        session()->flash('success', 'Faq Question successfully created.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.pages.faq.question.index", ["faq_id" => $this->faq->id]);
    }
}
