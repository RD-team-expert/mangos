<?php

use App\Console\Commands\GenerateDailyTasks;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tasks:generate-daily', function () {
    Artisan::call('app:generate-daily-tasks');
})->dailyAt('14:55');



