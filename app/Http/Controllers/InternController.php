<?php

namespace App\Http\Controllers;

use App\Models\Intern;

class InternController extends Controller
{
    /**
     * Display the specified intern details.
     * Kept for Chart.js integration.
     */
    public function show(Intern $intern)
    {
        $intern->load(['user', 'supervisor', 'tasks', 'attendances', 'assessments']);
        
        // Calculate statistics
        $stats = [
            'totalTasks' => $intern->tasks->count(),
            'completedTasks' => $intern->tasks->where('status', 'completed')->count(),
            'pendingTasks' => $intern->tasks->whereIn('status', ['pending', 'in_progress'])->count(),
            'attendancePercentage' => $intern->getAttendancePercentage(),
            'averageSpeed' => $intern->getAverageSpeed(),
            'overallScore' => $intern->getOverallScore(),
        ];

        // Chart data
        $taskStatusData = [
            'completed' => $intern->tasks->where('status', 'completed')->count(),
            'in_progress' => $intern->tasks->where('status', 'in_progress')->count(),
            'pending' => $intern->tasks->where('status', 'pending')->count(),
            'revision' => $intern->tasks->where('status', 'revision')->count(),
        ];

        $attendanceData = [
            'present' => $intern->attendances->where('status', 'present')->count(),
            'late' => $intern->attendances->where('status', 'late')->count(),
            'absent' => $intern->attendances->where('status', 'absent')->count(),
            'sick' => $intern->attendances->where('status', 'sick')->count(),
            'permission' => $intern->attendances->where('status', 'permission')->count(),
        ];

        return view('interns.show', compact('intern', 'stats', 'taskStatusData', 'attendanceData'));
    }
}
