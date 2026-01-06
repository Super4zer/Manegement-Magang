<?php

namespace App\Livewire\Assessments;

use App\Models\Assessment;
use App\Models\Intern;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]

class AssessmentIndex extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $intern_id = '';

    protected $queryString = [
        'intern_id' => ['except' => ''],
    ];

    public function updatingInternId()
    {
        $this->resetPage();
    }

    public function deleteAssessment($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        session()->flash('success', 'Penilaian berhasil dihapus!');
    }

    public function render()
    {
        $query = Assessment::with(['intern.user', 'task', 'assessedBy']);

        if ($this->intern_id) {
            $query->where('intern_id', $this->intern_id);
        }

        $assessments = $query->latest()->paginate(10);
        $interns = Intern::with('user')->where('status', 'active')->get();

        return view('livewire.assessments.assessment-index', compact('assessments', 'interns'));

    }
}
