<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DateTimePicker extends Component
{
    public $modelName;

    /**
     * Create a new component instance.
     *
     * @param $modelName
     */
    public function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

    public function updated()
    {
        dd($this->{$modelName});
        $this->emit('nameToParent',$this->{$modelName});
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.date-time-picker');
    }
}
