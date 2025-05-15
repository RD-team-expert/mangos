<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    /**
     * Get the current time.
     *
     * @return array
     */
    public function getCurrentTime()
    {
        // Get current time using Carbon
        $currentTime = Carbon::now()->toDateTimeString(); // Format: Y-m-d H:i:s
        $formattedTime = Carbon::now()->format('h:i:s A'); // Format: 12-hour with AM/PM

        return [
            'current_time' => $currentTime,
            'formatted_time' => $formattedTime,
        ];
    }

    /**
     * Display the current time in a view.
     *
     * @return \Illuminate\View\View
     */
    public function showTime()
    {
        $timeData = $this->getCurrentTime();
        return view('time', $timeData);
    }
}
