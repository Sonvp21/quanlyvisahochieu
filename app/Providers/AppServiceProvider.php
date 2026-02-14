<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Passport;
use App\Models\Visa;
use App\Observers\PassportObserver;
use App\Observers\VisaObserver;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Đăng ký Observers
        Passport::observe(PassportObserver::class);
        Visa::observe(VisaObserver::class);

       
    }
}
