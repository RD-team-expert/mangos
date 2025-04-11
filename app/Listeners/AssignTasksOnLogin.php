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
        $now = Carbon::now();

        // Define the time window (11:00 AM to 11:15 AM)
        $startTime = Carbon::today()->setTime(12, 57);
        $endTime = Carbon::today()->setTime(18, 18);
//dd($now->between($startTime, $endTime));
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
