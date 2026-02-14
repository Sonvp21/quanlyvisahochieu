<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment('Inspiring quote');
});

// Gửi mail nhắc hết hạn hộ chiếu & visa tự động
Schedule::command('notifications:send-expiry')
    //->everyMinute(); // test
    ->dailyAt('07:45');
