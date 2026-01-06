<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Intern;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Report::with(['intern.user', 'createdBy']);

        // If intern, only show their own reports
        if ($user->isIntern() && $user->intern) {
            $query->where('intern_id', $user->intern->id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->intern_id);
        }

        $reports = $query->latest()->paginate(10);
        $interns = Intern::with('user')->where('status', 'active')->get();
        
        return view('reports.index', compact('reports', 'interns'));
    }

    public function create()
    {
        $interns = Intern::with('user')->where('status', 'active')->get();
        return view('reports.create', compact('interns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:weekly,monthly,final',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        Report::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => 'submitted',
        ]);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    public function show(Report $report)
    {
        $report->load(['intern.user', 'createdBy']);
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $interns = Intern::with('user')->where('status', 'active')->get();
        return view('reports.edit', compact('report', 'interns'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:weekly,monthly,final',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'status' => 'required|in:draft,submitted,reviewed',
            'feedback' => 'nullable|string',
        ]);

        $report->update($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }

    // Add feedback to report
    public function addFeedback(Request $request, Report $report)
    {
        $validated = $request->validate([
            'feedback' => 'required|string',
        ]);

        $report->update([
            'feedback' => $validated['feedback'],
            'status' => 'reviewed',
        ]);

        return back()->with('success', 'Feedback berhasil ditambahkan!');
    }
}
