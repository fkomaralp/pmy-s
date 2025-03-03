<?php

namespace App\View\Components;

use App\Models\Configuration;
use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {

        $favicon = Configuration::getValue("FAVICON");

        return view('layouts.app', compact("favicon"));
    }
}
