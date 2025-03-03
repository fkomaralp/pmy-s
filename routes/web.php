<?php

use App\Models\Configuration;
use App\Models\Photos;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function() {

    $umi = \App\Models\UploadManagerImages::find(20);

    \App\Classes\ImagesStatusUpdater::progressing($umi, "997169b0-6533-4f73-a23e-c7f74e9d9d44");

});

Route::get('/clear', function () {
    echo Artisan::call('cache:clear');
    echo Artisan::call('view:clear');
    echo Artisan::call('config:clear');
    echo Artisan::call('config:cache');
    echo Artisan::call('event:clear');
//    echo Artisan::call('queue:work --queue=delete_user_files --tries=2 --stop-when-empty');
});
Route::get('/mail', function () {
    return view("emails.send_images_to_user_email", [
        "event_title" => "Test LessThan50",
        "url" => asset("user_images/ffperl76gmailcom.zip"),
        "logo" => Configuration::getValue("LOGO"),
        "favicon" => Configuration::getValue("FAVICON"),
        "order_number" => "8H9230416X615491P",
        "download_url" => "https://picmyrun.co.uk/mail",
        "event_code" => "ZPWH-B3OC",
        "order" => \App\Models\Order::find(29),
        "mail" => Configuration::getValue("EMAIL")
    ]);
});

//Route::get('/download/{file}', function ($file) {
//
//    $headers = array(
//        'Content-Type: application/zip, application/octet-stream',
//    );
//
//    return Response::download(public_path("user_images/".$file), $file, $headers);
//
//})->name("download.zip");

Route::middleware(\App\Http\Middleware\SetCartToken::class)
    ->name("frontend.")->group(function () {
        Route::get('/', [\App\Http\Controllers\HomePageController::class, 'index'])
            ->name("index");

        Route::get('event/{event_id}/{event_title}', [\App\Http\Controllers\EventDetailsController::class, 'show'])
            ->name("event.access.show");

        Route::get('event/{event_id}/details/{bib_number}/preview', [\App\Http\Controllers\EventPreviewController::class, 'show'])
            ->middleware(\App\Http\Middleware\CheckPreviewPageType::class)
            ->name("event.access.preview");

//        Route::middleware(["auth:sanctum"])->group(function(){

        Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])
            ->name("cart.index");

//        });

        Route::prefix("payment/process")
            ->name("payment.process")->group(function () {

                Route::prefix("stripe")->group(function(){

                    Route::post('/', [\App\Http\Controllers\StripeController::class, 'paymentAction'])
                        ->name(".index");

                    Route::any('success', [\App\Http\Controllers\StripeController::class, 'success'])
                        ->name(".success");

                    Route::any('error', [\App\Http\Controllers\StripeController::class, 'error'])
                        ->name(".error");

                });

                Route::prefix("paypal")->name(".paypal")->group(function(){

                    Route::get('', [\App\Http\Controllers\PaypalController::class, 'payment'])
                        ->name(".payment");

                    Route::get('cancel', [\App\Http\Controllers\PaypalController::class, 'cancel'])
                        ->name(".cancel");

                    Route::any('success', [\App\Http\Controllers\PaypalController::class, 'success'])
                        ->name(".success");

                    Route::any('error', [\App\Http\Controllers\PaypalController::class, 'error'])
                        ->name(".error");

                });
            });

        Route::get('about', [\App\Http\Controllers\AboutController::class, 'index'])
            ->name("about.index");

        Route::get('faq', [\App\Http\Controllers\FAQController::class, 'index'])
            ->name("faq.index");

        Route::get('contact', [\App\Http\Controllers\ContactController::class, 'index'])
            ->name("contact.index");

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::prefix("dashboard")
        ->name("dashboard.")->group(function () {

            Route::get('/', \App\Http\Livewire\Dashboard\Index::class)->name("index");

        Route::prefix("events")
            ->name("events.")->group(function () {
            Route::get('/', \App\Http\Livewire\Dashboard\Events\Index::class)->name("index");
            Route::get('/create', \App\Http\Livewire\Dashboard\Events\Create::class)->name("create");
            Route::get('{event_id}/edit', \App\Http\Livewire\Dashboard\Events\Edit::class)->name("edit");
        });

        Route::prefix("analysis")
            ->name("analysis.")->group(function () {

            Route::
            get('/upload_manager', \App\Http\Livewire\Dashboard\Analysis\UploadManager::class)
            ->name("upload_manager");

            Route::
            get('/image_analysis', \App\Http\Livewire\Dashboard\Analysis\ImageAnalysis::class)
            ->name("image_analysis");


            Route::prefix("manual_tagging")
                ->name("manual_tagging.")->group(function () {

                    Route::
                    get('/', \App\Http\Livewire\Dashboard\Analysis\ManualTagging\Index::class)
                        ->name("index");

                    Route::
                    get('/{bib_number_file}/edit', \App\Http\Livewire\Dashboard\Analysis\ManualTagging\Edit::class)
                        ->name("edit");

                    Route::
                    get('/multiple', \App\Http\Livewire\Dashboard\Analysis\ManualTagging\Multiple::class)
                        ->name("multiple");
            });

            Route::prefix("settings/watermark")
                ->name("settings.watermark.")->group(function () {

                    Route::prefix("thumbnail")
                        ->name("thumbnail.")->group(function () {
                            Route::get('/', \App\Http\Livewire\Dashboard\Analysis\Settings\Thumbnail::class)->name("index");
                    });

                    Route::prefix("sponsored")
                        ->name("sponsored.")->group(function () {
                            Route::get('/', \App\Http\Livewire\Dashboard\Analysis\Settings\Sponsored::class)->name("index");
                    });

                    Route::prefix("free")
                        ->name("free.")->group(function () {
                            Route::get('/', \App\Http\Livewire\Dashboard\Analysis\Settings\Free::class)->name("index");
                    });
                });
        });

        Route::prefix("orders")
            ->name("orders.")->group(function () {
            Route::get('/', \App\Http\Livewire\Dashboard\Orders\Index::class)->name("index");
        });

        Route::prefix("pages")
            ->name("pages.")->group(function () {

                Route::prefix("faq")
                    ->name("faq.")->group(function () {
                    Route::get('/', \App\Http\Livewire\Dashboard\Faq\Index::class)->name("index");
                    Route::get('/create', \App\Http\Livewire\Dashboard\Faq\Create::class)->name("create");
                    Route::get('{faq_id}/edit', \App\Http\Livewire\Dashboard\Faq\Edit::class)->name("edit");

                    Route::prefix("question")
                        ->name("question.")->group(function () {
                            Route::get('/{faq_id}', \App\Http\Livewire\Dashboard\Faq\Question\Index::class)->name("index");
                            Route::get('/{faq_id}/create', \App\Http\Livewire\Dashboard\Faq\Question\Create::class)->name("create");
                            Route::get('{question_id}/edit', \App\Http\Livewire\Dashboard\Faq\Question\Edit::class)->name("edit");
                        });

                });
        });

        Route::prefix("prices")
            ->name("priceses.")->group(function () {
            Route::get('/', \App\Http\Livewire\Dashboard\Priceses\Index::class)->name("index");
            Route::get('/create', \App\Http\Livewire\Dashboard\Priceses\Create::class)->name("create");
            Route::get('{price_id}/edit', \App\Http\Livewire\Dashboard\Priceses\Edit::class)->name("edit");
        });

        Route::prefix("settings")
            ->name("settings.")->group(function () {
            Route::get('/', \App\Http\Livewire\Dashboard\Settings\Index::class)->name("index");

            Route::prefix("social_media")
                ->name("social_media.")->group(function () {
                    Route::get('/', \App\Http\Livewire\Dashboard\SocialMedia\Index::class)->name("index");
                });

            Route::prefix("mail")
                ->name("mail.")->group(function () {
                    Route::get('/', \App\Http\Livewire\Dashboard\Mail\Index::class)->name("index");
                });
        });

        Route::prefix("locations")
            ->name("locations.")->group(function () {

                Route::prefix("countries")
                    ->name("countries.")->group(function () {

                    Route::get('/', \App\Http\Livewire\Dashboard\Countries\Index::class)->name("index");

                });

                Route::prefix("cities")
                    ->name("cities.")->group(function () {

                    Route::get('/', \App\Http\Livewire\Dashboard\Cities\Index::class)->name("index");
                    Route::get('{city_id}/edit', \App\Http\Livewire\Dashboard\Cities\Edit::class)->name("edit");
                    Route::get('create', \App\Http\Livewire\Dashboard\Cities\Create::class)->name("create");
                });

        });
    });
});

//Route::any('/tus/{any?}', function () {
//    return app('tus-server')->serve();
//})->where('any', '.*');

\URL::forceScheme('https');


