<?php

namespace App\Livewire\Attendances;

use App\Models\Attendance;
use App\Models\Intern;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Presensi Magang')]
class AttendanceIndex extends Component
{
    use WithPagination;

    public $date = '';
    public $month = '';
    public $status = '';
    public $intern_id = '';

    protected $queryString = [
        'date' => ['except' => ''],
        'month' => ['except' => ''],
        'status' => ['except' => ''],
        'intern_id' => ['except' => ''],
    ];

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function updatingMonth()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingInternId()
    {
        $this->resetPage();
    }

    public function deleteAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        session()->flash('success', 'Presensi berhasil dihapus!');
    }

    public function render()
    {
        $user = auth()->user();
        $query = Attendance::with(['intern.user']);

        // If user is intern, only show their attendances
        if ($user->isIntern()) {
            $query->where('intern_id', $user->intern->id);
        }

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        if ($this->month) {
            $query->whereYear('date', substr($this->month, 0, 4))
                  ->whereMonth('date', substr($this->month, 5, 2));
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->intern_id && $user->canManage()) {
            $query->where('intern_id', $this->intern_id);
        }

        $attendances = $query->latest('date')->paginate(10);
        $interns = Intern::with('user')->where('status', 'active')->get();

        return view('livewire.attendances.attendance-index', compact('attendances', 'interns'));
    }
}
