<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignTasksOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event)
    {

        $user = $event->user;
        // Set the timezone to America/New_York (Columbus, Ohio)
        $now = Carbon::now('America/New_York');

        // Define the time window (5:00 AM to 5:15 AM in Columbus timezone)
        $startTime = Carbon::today('America/New_York')->setTime(5, 0);
        $endTime = Carbon::today('America/New_York')->setTime(5, 15);
        if ($now->between($startTime, $endTime)) {
            // Get all users who logged in between 11:00 AM and 11:15 AM today
            $recentUsers = User::whereNotNull('last_login_at')
                ->whereBetween('last_login_at', [$startTime, $endTime])
                ->get();

            // Get all incomplete "open" tasks
            $incompleteTasks = Task::where('section', 'open')
                ->where('is_completed', false)
                ->get();

            if ($incompleteTasks->isNotEmpty() && $recentUsers->isNotEmpty()) {
                // Reset user_id for all incomplete tasks
                $incompleteTasks->each(function ($task) {
                    $task->update(['user_id' => null]);
                });

                // Distribute tasks evenly among recent users
                $tasksPerUser = ceil($incompleteTasks->count() / $recentUsers->count());

                $incompleteTasks->chunk($tasksPerUser)->each(function ($taskChunk, $index) use ($recentUsers) {
                    if ($index < $recentUsers->count()) {
                        $assignedUser = $recentUsers[$index];
                        $taskChunk->each(function ($task) use ($assignedUser) {
                            $task->update(['user_id' => $assignedUser->id]);
                        });
                    }
                });
            }
        }

        // Update last login time
        $user->update(['last_login_at' => $now]);
    }
}
