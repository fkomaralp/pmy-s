<?php

namespace App\Providers;

use App\Models\Configuration;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
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
//
//        (new \Illuminate\Mail\MailServiceProvider(app()))->register();
    }
}
