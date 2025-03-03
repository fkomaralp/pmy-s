<?php

namespace App\Providers;

use App\Events\ImageUploaded;
use App\Events\ImageUploadedWithoutAnalysis;
use App\Events\OrderCreated;
use App\Listeners\ImageAnalysis;
use App\Listeners\ImageAnalysisWithoutAnalysis;
use App\Listeners\SendImagesToUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            SendImagesToUser::class,
        ],
        ImageUploaded::class => [
            ImageAnalysis::class,
        ]
//        ImageUploadedWithoutAnalysis::class => [
//            ImageAnalysisWithoutAnalysis::class,
//        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
