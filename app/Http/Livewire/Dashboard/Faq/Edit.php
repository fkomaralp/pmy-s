<?php

namespace App\Http\Livewire\Dashboard\Faq;

use App\Actions\Dashboard\Faq\Update as FaqUpdate;
use App\Models\Faq;
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
    public $faq;

    public function mount($faq_id)
    {
        $this->faq = Faq::find($faq_id);
        $this->state["title"] = $this->faq->title;
    }

    /**
     * Create a new team.
     *
     * @param FaqUpdate $creator
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateTitle(FaqUpdate $creator)
    {
        $this->resetErrorBag();

        $creator->update($this->faq, $this->state);

        return $this->redirectPath($creator);
    }

    public function render()
    {
        return view('livewire.dashboard.faq.edit');
    }

    public function back()
    {
        return redirect(url()->route("dashboard.pages.faq.index"));
    }
}
