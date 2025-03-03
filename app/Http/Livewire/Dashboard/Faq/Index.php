<?php

namespace App\Http\Livewire\Dashboard\Faq;

use App\Models\Event;
use App\Models\Faq;
use App\Models\Price;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Index extends Component
{
    use RedirectsActions;

    public $delete_id;

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function delete()
    {
        Faq::destroy($this->delete_id);

        session()->flash('success', 'Title successfully deleted.');

        return redirect()->to(url()->previous());
    }

    public function render()
    {
        $faqs = Faq::all();

        return view('livewire.dashboard.faq.index', ["faqs" => $faqs]);
    }
}
