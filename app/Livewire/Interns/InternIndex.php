<?php

namespace App\Livewire\Interns;

use App\Models\Intern;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Daftar Anggota Magang')]
class InternIndex extends Component
{
    use WithPagination;



    public $search = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deleteIntern($id)
    {
        $intern = Intern::findOrFail($id);
        $user = $intern->user;
        $intern->delete();
        $user->delete();

        session()->flash('success', 'Anggota magang berhasil dihapus!');
    }

    public function render()
    {
        $query = Intern::with(['user', 'supervisor']);

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('school', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $interns = $query->latest()->paginate(10);

        return view('livewire.interns.intern-index', compact('interns'));
    }
}
