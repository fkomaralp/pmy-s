<?php

namespace App\Http\Livewire\Dashboard\Analysis\Settings;

use App\Models\Configuration;
use Intervention\Image\ImageManagerStatic;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Thumbnail extends Component
{
    use RedirectsActions, WithFileUploads;

    public $title = "Thumbnail";
    public $image;

    public function mount()
    {
        $this->image = Configuration::getValue("THUMBNAIL_WATERMARK");
    }

    public function update()
    {
        if(is_a($this->image, TemporaryUploadedFile::class)){
            $path = $this->image->store("public");
            $path = storage_path("app/public".str_replace("public", "", $path));

            $filename = public_path("storage/bib_number_thumbnail_watermark.png");

            ImageManagerStatic::configure(['driver' => 'imagick']);
            $png = ImageManagerStatic::make($path)->encode('png');

            $png->save($filename);

            $THUMBNAIL_WATERMARK = "/storage/bib_number_thumbnail_watermark.png";
            Configuration::updateOrCreate(["name" => "THUMBNAIL_WATERMARK"], ["value" => $THUMBNAIL_WATERMARK]);
        }

        $this->emit("saved");
    }

    public function render()
    {
        return view('livewire.dashboard.analysis.settings.thumbnail');
    }
}
