<?php

namespace App\Http\Livewire\Dashboard\Faq;

use Illuminate\Support\Facades\Auth;
use App\Actions\Dashboard\Faq\Create as FaqCreate;
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

    public function mount()
    {
    }

    public function createTitle(FaqCreate $creator)
    {
        $this->resetErrorBag();

        $creator->create($this->state);

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
        return view('livewire.dashboard.faq.create');
    }

    public function back()
    {
        return redirect(url()->route("dashboard.pages.faq.index"));
    }
}
