<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    public function view()
    {
        $user = Auth::user();
        $attendanceList = Attendance::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        return view('dashboard', ['attendanceList' => $attendanceList]);
    }

    public function insert_data(Request $request)
    {
        $workHoursArray = [];
        $breakHoursArray = [];
        $user = Auth::user();
        $breakInterval = 3;
        $breakDuration = 1;
        
        switch ($request->shift) {
            case 'shifta':
                $shiftStart = 6;
                $workingHours = $request->hours;
                $startHour = $this->formatTime($shiftStart);
                $endHour = $this->formatTime($shiftStart + $request->hours);

                break;
            case 'shiftb':
                $shiftStart = 18;
                $workingHours = $request->hours;
                $startHour = $this->formatTime($shiftStart);
                $endHour = $this->formatTime($shiftStart + $request->hours);

                break;
        }

        // Loop through each hour of work
        for ($i = 0; $i < $workingHours; $i++) {
            // Calculate the current hour based on the selected start time
            $currentHour = $shiftStart + $i;

            // Format the time ranges
            $startTime = $this->formatTime($currentHour);
            $endTime = $this->formatTime(($currentHour + 1) % 24);

            // Push the formatted working hour
            $workHoursArray[] = "Working Hour: $startTime - $endTime";

            // Check if it's time for a break
            if (($i + 1) % $breakInterval == 0) {
                // Calculate break start and end times
                $breakStartTime = $this->formatTime(($currentHour + 1) % 24);
                $breakEndTime = $this->formatTime(($currentHour + 1 + $breakDuration) % 24);
                
                $workHoursArray[] = "Break Hour: $breakStartTime - $breakEndTime";
                $breakHoursArray[] = "$breakStartTime - $breakEndTime";
                
                // Increment $i to skip the next hour since it's break time
                $i++;
            }
        }


        // Start get last working hours
        $lastArray = ($request->hours) - 1;
        $lastWorkingHour = $workHoursArray[$lastArray];

        preg_match('/\d{2}:\d{2} [APMapm]{2}/', $lastWorkingHour, $matches);
        $endingTimeRegex = $matches[0];
        $hyphenPosition = strpos($lastWorkingHour, '-');
        $endingTimeSubstring = trim(substr($lastWorkingHour, $hyphenPosition + 1));
        // End last working hours

        // Change array to string
        $break = implode(', ', $breakHoursArray);
        $workingHoursDisplay = implode(', ', $workHoursArray);

        $attendance = Attendance::orderBy('id','desc')->first();
        // dd($attendance->id);
        if ($attendance === null){
            $attendanceId = 'ATT' . 0 . 0 . 1;    
        } else {
            $auto_inc_prd = $attendance->id + 1;

            // dd($auto_inc_prd);
            $attendanceId = 'ATT' . 0 . 0 . $auto_inc_prd;    
        }

        Attendance::create([
            'user_id' => $user->id,
            'attendance_id' => $attendanceId,
            'attendance_name' => $user->name,
            'date' => $request->date,
            'shift' => $request->shift,
            'hours' => $request->hours,
            'start_time' => $startHour,
            'end_time' => $endingTimeSubstring,
            'break_time' => $break,
        ]);

        // To display the test result
        // dd($workHoursArray);

        $attendanceList = Attendance::where('user_id', $user->id)->get();
        return redirect('/dashboard')
        ->with('add-success', 'Attendance Successfully Created')
        ->with('workingHoursDisplay', $workingHoursDisplay);
        // return response()->json($workHoursArray);
    }

    // Function to format time to 12-hour format with AM/PM
    private function formatTime($hour)
    {
        $formattedHour = $hour % 12;
        if ($formattedHour == 0) {
            $formattedHour = 12;
        }

        $amPm = ($hour < 12) ? 'AM' : 'PM';

        return sprintf("%02d:00 %s", $formattedHour, $amPm);
    }
}
