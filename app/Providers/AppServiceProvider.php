<?php

namespace App\Providers;

use DB;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $generator): void
    {
        if (env("APP_ENV") == "production") {
            $generator->forceScheme("https");
        }

        Route::model("notification", DatabaseNotification::class);

        if (env("APP_ENV") !== "production") {
            DB::listen(function (QueryExecuted $query) {
                // Log::info("[Query] {$query->toRawSql()} took {$query->time}ms");
            });
        }
    }
}
