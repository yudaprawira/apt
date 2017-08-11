<?php

use Illuminate\Foundation\Inspiring;
use Modules\News\Http\Controllers\CronGrabController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('grab-kl', function () {
    $this->comment(CronGrabController::cron_grabKL());
});
Artisan::command('grab-brilio', function () {
    $this->comment(CronGrabController::cron_grabBrilio());
});
Artisan::command('grab-bola', function () {
    $this->comment(CronGrabController::cron_grabBola());
});
Artisan::command('grab-merdeka', function () {
    $this->comment(CronGrabController::cron_grabMerdeka());
});

