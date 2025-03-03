<?php

namespace App\Http\Livewire\Dashboard\Faq\Question;

use App\Models\Faq;
use Illuminate\Support\Facades\Auth;
use App\Actions\Dashboard\Faq\Question\Create as QuestionCreate;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Create extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];
    public $faq;

    public function mount($faq_id)
    {
        $this->faq = Faq::find($faq_id);
    }

    public function createQuestion(QuestionCreate $creator)
    {
        $this->resetErrorBag();

        $this->state["faq_id"] = $this->faq->id;

        $creator->create($this->faq, $this->state);

        return $this->redirectPath($creator);
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }


    public function render()
    {
        return view('livewire.dashboard.faq.question.create');
    }

    public function back()
    {
        return redirect(url()->route("dashboard.pages.faq.question.index", ["faq_id" => $this->faq->id]));
    }
}
