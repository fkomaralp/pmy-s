<?php

namespace App\Http\Livewire\Dashboard\Analysis\Settings;

use App\Models\Configuration;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Free extends Component
{
    use RedirectsActions, WithFileUploads;

    public $title = "Free";
    public $image;

    public function mount()
    {
        $this->image = Configuration::getValue("WATERMARK_FOR_FREE_PRICE");
    }

    public function update()
    {
        if(is_a($this->image, TemporaryUploadedFile::class)){
            $path = $this->image->store("public");
            $WATERMARK_FOR_FREE_PRICE = "/".str_replace("public", "storage", $path);
            Configuration::updateOrCreate(["name" => "WATERMARK_FOR_FREE_PRICE"], ["value" => $WATERMARK_FOR_FREE_PRICE]);
        }

        $this->emit("saved");
    }

    public function render()
    {
        return view('livewire.dashboard.analysis.settings.free');
    }
}
