<?php

namespace App\Http\Livewire\Dashboard\Analysis\Settings;

use App\Models\Configuration;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Sponsored extends Component
{
    use RedirectsActions, WithFileUploads;

    public $title = "Sponsored";
    public $vertical_image;
    public $horizontal_image;
    public $fit_image_to_width;
    public $orientation = 0;
    public $opacity = 1;
    public $width = 50;
    public $position;

    public function mount()
    {
//        $this->orientation = Configuration::getValue("SPONSOR_ORIENTATION");
//        $this->width = Configuration::getValue("SPONSOR_WIDTH");
        $this->opacity = Configuration::getValue("SPONSOR_OPACITY");
        $this->vertical_image = Configuration::getValue("SPONSOR_VERTICAL_IMAGE");
        $this->horizontal_image = Configuration::getValue("SPONSOR_HORIZONTAL_IMAGE");
        $this->position = Configuration::getValue("SPONSOR_POSITION");
        $this->fit_image_to_width = boolval(Configuration::getValue("FIT_IMAGE_TO_WIDTH"));
    }

    public function updatePosition($position)
    {
        $this->position = $position;
    }

    public function update()
    {

        if(is_a($this->vertical_image, TemporaryUploadedFile::class)){
            $path = $this->vertical_image->store("public");
            $SPONSOR_VERTICAL_IMAGE = "/".str_replace("public", "storage", $path);
            Configuration::updateOrCreate(["name" => "SPONSOR_VERTICAL_IMAGE"], ["value" => $SPONSOR_VERTICAL_IMAGE]);
        }

        if(is_a($this->horizontal_image, TemporaryUploadedFile::class)){
            $path = $this->horizontal_image->store("public");
            $SPONSOR_HORIZONTAL_IMAGE = "/".str_replace("public", "storage", $path);
            Configuration::updateOrCreate(["name" => "SPONSOR_HORIZONTAL_IMAGE"], ["value" => $SPONSOR_HORIZONTAL_IMAGE]);
        }

//        Configuration::updateOrCreate(["name" => "SPONSOR_ORIENTATION"], ["value" => $this->orientation]);
        Configuration::updateOrCreate(["name" => "SPONSOR_OPACITY"], ["value" => intval($this->opacity * 100)]);
        Configuration::updateOrCreate(["name" => "SPONSOR_POSITION"], ["value" => $this->position]);
        Configuration::updateOrCreate(["name" => "FIT_IMAGE_TO_WIDTH"], ["value" => (int)$this->fit_image_to_width]);
//        Configuration::updateOrCreate(["name" => "SPONSOR_WIDTH"], ["value" => $this->width]);

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.dashboard.analysis.settings.sponsored');
    }
}
