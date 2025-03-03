<?php

namespace App\Actions\Dashboard\Faq\Question;

use App\Models\Question;
use Illuminate\Support\Facades\Validator;

class Update
{
    public $question;

    public function update(Question $question, array $input)
    {
        $this->question = $question;
        Validator::make($input, [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
        ])->validate();

        $question->fill($input)->save();

        session()->flash('success', 'Question successfully updated.');
    }

    public function redirectTo()
    {
        return url()->route("dashboard.pages.faq.question.index", ["faq_id" => $this->question->faq_id]);
    }
}
