<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WatermarkSettings extends Component
{
    public $title;

    public function mount($title)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.dashboard.analysis.settings.layout');
    }
}
