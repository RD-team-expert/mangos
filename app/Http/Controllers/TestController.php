<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateDailyTasks;
use App\Models\Task;
use App\Models\TaskImage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function testCron()
    {
        try {
            // Manually run the GenerateDailyTasks command
            $exitCode = Artisan::call('app:generate-daily-tasks');

            // Fetch the latest status of daily tasks and images
            $dailyTasks = Task::where('is_daily', true)->get();
            $taskCount = $dailyTasks->count();
            $completedCount = $dailyTasks->where('is_completed', false)->count();
            $imageCount = TaskImage::whereIn('task_id', $dailyTasks->pluck('id'))->count();

            $message = $exitCode === 0
                ? "Cron test successful. Reset $taskCount daily tasks, $completedCount are now pending, and $imageCount images remain."
                : "Cron test failed with exit code $exitCode.";

            return view('test', [
                'message' => $message,
                'tasks' => $dailyTasks,
                'image_count' => $imageCount,
                'current_time' => now()->format('h:i:s A'),
            ]);
        } catch (\Exception $e) {
            Log::error('Cron test failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return view('test', [
                'message' => 'An error occurred during the cron test: ' . $e->getMessage(),
                'tasks' => [],
                'image_count' => 0,
                'current_time' => now()->format('h:i:s A'),
            ]);
        }
    }
}
