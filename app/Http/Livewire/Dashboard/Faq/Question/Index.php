<?php

namespace App\Http\Livewire\Dashboard\Faq\Question;

use App\Models\Faq;
use App\Models\Question;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Index extends Component
{
    use RedirectsActions;

    public $delete_id;
    public $faq;
    public $questions;

    public function mount($faq_id)
    {
        $this->faq = Faq::find($faq_id);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function delete()
    {
        Question::destroy($this->delete_id);

        session()->flash('success', 'Question successfully deleted.');

        return redirect()->to(url()->previous());
    }

    public function render()
    {
        $this->questions = $this->faq->questions;

        return view('livewire.dashboard.faq.question.index', ["questions" => $this->questions]);
    }
}
