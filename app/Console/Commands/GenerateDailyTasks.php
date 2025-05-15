<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateDailyTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Find all tasks marked as is_daily
            $dailyTasks = Task::where('is_daily', true)->get();

            if ($dailyTasks->isEmpty()) {
                $this->info('No daily tasks found to reset.');
                return;
            }
            $updatedCount = 0;
            $imagesDeletedCount = 0;

            foreach ($dailyTasks as $task) {
                // Reset task completion status
                $task->update([
                    'is_completed' => false,
                    'completed_at' => null,
                ]);
                $updatedCount++;

                // Find and delete associated images
                $taskImages = TaskImage::where('task_id', $task->id)->get();
                foreach ($taskImages as $taskImage) {
                    // Delete the image file from storage
                    if ($taskImage->image_path && Storage::exists($taskImage->image_path)) {
                        Storage::delete($taskImage->image_path);
                    }
                    // Delete the TaskImage record
                    $taskImage->delete();
                    $imagesDeletedCount++;
                }
            }
            // Log the update for debugging
            Log::info('Reset daily tasks', [
                'updated_count' => $updatedCount,
            ]);


            $this->info('Successfully generated ' . $dailyTasks->count() . ' daily tasks.');
        } catch (\Exception $e) {
            Log::error('Failed to generate daily tasks', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->error('An error occurred while generating daily tasks.');
        }
    }
}
