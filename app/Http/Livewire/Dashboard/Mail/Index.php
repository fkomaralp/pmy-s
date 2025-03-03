<?php

namespace App\Http\Livewire\Dashboard\Mail;

use App\Models\Configuration;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use App\Actions\Dashboard\Mail\Update as MailUpdate;
use Livewire\WithFileUploads;

class Index extends Component
{
    use RedirectsActions;

    public $state = [];

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updateMailSettings(MailUpdate $updater)
    {
        $this->resetErrorBag();

        $updater->update(array_merge($this->state, []));

        $this->emit('saved');
    }

    public function render()
    {
        $this->state["host"] = Configuration::getValue("host");
        $this->state["port"] = Configuration::getValue("port");
        $this->state["username"] = Configuration::getValue("username");
        $this->state["password"] = Configuration::getValue("password");
        $this->state["template"] = Configuration::getValue("template");

        return view('livewire.dashboard.mail.index');
    }
}
