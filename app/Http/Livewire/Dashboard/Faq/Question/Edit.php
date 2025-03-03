<?php

namespace App\Http\Livewire\Dashboard\Faq\Question;

use App\Actions\Dashboard\Faq\Question\Update as QuestionUpdate;
use App\Models\Question;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Edit extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];
    public $question;

    public function mount($question_id)
    {
        $this->question = Question::find($question_id);

        $this->state["question"] = $this->question->question;
        $this->state["answer"] = $this->question->answer;
    }

    /**
     * Create a new team.
     *
     * @param QuestionUpdate $creator
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateQuestion(QuestionUpdate $creator)
    {
        $this->resetErrorBag();

        $creator->update($this->question, $this->state);

        return $this->redirectPath($creator);
    }

    public function render()
    {
        return view('livewire.dashboard.faq.question.edit');
    }

    public function back()
    {
        return redirect(url()->route("dashboard.pages.faq.question.index", ["faq_id" => $this->question->faq_id]));
    }
}
