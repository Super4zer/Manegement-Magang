<?php

namespace App\Livewire\Tasks;

use App\Models\Intern;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Daftar Pekerjaan')]
class TaskIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $priority = '';
    public $intern_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
        'intern_id' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPriority()
    {
        $this->resetPage();
    }

    public function updatingInternId()
    {
        $this->resetPage();
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        session()->flash('success', 'Tugas berhasil dihapus!');
    }

    public function render()
    {
        $user = auth()->user();
        $query = Task::with(['intern.user', 'assignedBy']);

        // If user is intern, only show their tasks
        if ($user->isIntern()) {
            $query->where('intern_id', $user->intern->id);
        }

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        if ($this->intern_id && $user->canManage()) {
            $query->where('intern_id', $this->intern_id);
        }

        $tasks = $query->latest()->paginate(10);
        $interns = Intern::with('user')->where('status', 'active')->get();

        return view('livewire.tasks.task-index', compact('tasks', 'interns'));
    }
}
