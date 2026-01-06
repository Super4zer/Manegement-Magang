<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InternController extends Controller
{
    public function index(Request $request)
    {
        $query = Intern::with(['user', 'supervisor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('school', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $interns = $query->latest()->paginate(10);
        
        return view('interns.index', compact('interns'));
    }

    public function create()
    {
        $supervisors = User::whereIn('role', ['admin', 'pembimbing'])->get();
        return view('interns.create', compact('supervisors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nis' => 'nullable|string|max:50',
            'school' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function() use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'intern',
            ]);

            Intern::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'school' => $validated['school'],
                'department' => $validated['department'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'supervisor_id' => $validated['supervisor_id'],
                'status' => 'active',
            ]);
        });

        return redirect()->route('interns.index')
            ->with('success', 'Anggota magang berhasil ditambahkan!');
    }

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

    public function edit(Intern $intern)
    {
        $supervisors = User::whereIn('role', ['admin', 'pembimbing'])->get();
        return view('interns.edit', compact('intern', 'supervisors'));
    }

    public function update(Request $request, Intern $intern)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $intern->user_id,
            'nis' => 'nullable|string|max:50',
            'school' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'supervisor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        DB::transaction(function() use ($validated, $intern) {
            $intern->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $intern->update([
                'nis' => $validated['nis'],
                'school' => $validated['school'],
                'department' => $validated['department'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'supervisor_id' => $validated['supervisor_id'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('interns.index')
            ->with('success', 'Data anggota magang berhasil diperbarui!');
    }

    public function destroy(Intern $intern)
    {
        $user = $intern->user;
        $intern->delete();
        $user->delete();

        return redirect()->route('interns.index')
            ->with('success', 'Anggota magang berhasil dihapus!');
    }
}
