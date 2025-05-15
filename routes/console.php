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
})->dailyAt('13:55');



Artisan::command('tasks:generate-daily', function () {
    try {
        $exitCode = Artisan::call('app:generate-daily-tasks');
        if ($exitCode === 0) {
            $this->info('Daily tasks generated successfully.');
        } else {
            $this->error('Failed to generate daily tasks.');
            Log::error('tasks:generate-daily command failed', ['exit_code' => $exitCode]);
        }
    } catch (\Exception $e) {
        $this->error('An error occurred while generating daily tasks.');
        Log::error('tasks:generate-daily command exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
})->purpose('Generate daily tasks and reset their status')
    ->dailyAt('13:55');
