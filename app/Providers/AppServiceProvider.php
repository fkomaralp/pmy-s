<?php

namespace App\Providers;

use App\Jobs\PrepareImageFileJob;
use App\Models\Cart;
use App\Models\Configuration;
use App\View\Components\ImageAnalysis;
use App\View\Components\WatermarkSettings;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

//        $storage = new StorageClient([
//            'keyFilePath' => config_path("googlevisionkey.json")
//        ]);
//
//        $bucket = $storage->bucket("race_for_life_maidstone");
//        $object = $bucket->object("SPLB8007.JPG");
//        $path = storage_path("app/cloud-storage-tmp/fkomaralpgmailcom");
//        $object->downloadToFile($path."/SPLB8007.JPG");
//        $or = Order::find(25);
//        $r = new SendImagesToUserJob($or);
//
//        dd($r->addSponsoredWatermarkToImage("/var/www/html/storage/app/cloud-storage-tmp/fkomaralpgmailcom/SPLB8007.JPG", Event::find(23)));
//        SendImagesToUserJob::dispatch(Order::find(1));

//        $client = new ImageAnnotatorClient([
//            "credentials" => json_decode(file_get_contents(public_path("js/googlevisionkey.json")), true)
//        ]);
//
//        $image_path = "/var/www/html/public/SPLB8110.jpeg";
//        $image = fopen($image_path, 'r');
//
//        $response = $client->textDetection($image);
//        $data = $response->serializeToJsonString();
//
//        $bib_numbers = [];
////        $bib_number_block = "";
//
//        $json_test = Helper::isJson($data);
//
//        if($json_test){
//            $data = json_decode($data);
//
//            if(isset($data->textAnnotations) && is_array($data->textAnnotations) && count($data->textAnnotations) > 0){
//                $description = $data->textAnnotations[0]->description;
//                echo $description;
//
//                $description_array = explode("\n", $description);
//
//                foreach($description_array as $bib_number){
//                    preg_match('/^\d+$/', $bib_number, $matches);
//                    if($matches){
//                        $bib_numbers[] = $bib_number;
//                    }
//                }
//
//                if(count($bib_numbers) > 0){
//                    $bib_number_block = implode("-", $bib_numbers);
//                }
//            }
//        }
//        exit;

        \View::share('title', Configuration::getValue("TITLE"));
        \View::share('logo', Configuration::getValue("LOGO"));
        \View::share('favicon', Configuration::getValue("FAVICON"));
        \View::share('email', Configuration::getValue("EMAIL"));

        \View::share('EVENTS_PASSWORD_PROTECTED', boolval(Configuration::getValue("EVENTS_PASSWORD_PROTECTED")));
        \View::share('DEFAULT_EVENT_IMAGE', Configuration::getValue("DEFAULT_EVENT_IMAGE"));

        \View::share('linkedin', Configuration::getValue("LINKEDIN"));
        \View::share('twitter', Configuration::getValue("TWITTER"));
        \View::share('facebook', Configuration::getValue("FACEBOOK"));
        \View::share('instagram', Configuration::getValue("INSTAGRAM"));

        $count = 0;

        if(request()->cookie("token") !== null){
            $cart = Cart::where("token", request()->cookie("token"))->get();
            $count = $cart->count();
        }

        \View::share("cart_count", $count);

        Livewire::component("watermark-setttings", WatermarkSettings::class);
        Livewire::component("image-analysis", ImageAnalysis::class);

//        Config::set('mail.mailers.smtp.host', DB::table('configurations')->where("name", "host")->first("value")->value);
//        Config::set('mail.mailers.smtp.transport', "smtp");
//        Config::set('mail.mailers.smtp.port', (int)DB::table('configurations')->where("name", "port")->first("value")->value);
//        Config::set('mail.from', [
//            "address" => DB::table('configurations')->where("name", "username")->first("value")->value,
//            "name" => env("APP_NAME")
//        ]);
//        Config::set('mail.mailers.smtp.username', DB::table('configurations')->where("name", "username")->first("value")->value);
//        Config::set('mail.mailers.smtp.password', DB::table('configurations')->where("name", "password")->first("value")->value);
//        Config::set('mail.mailers.smtp.encryption', 'tls');
    }
}

