<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Models\User;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Set;

class Map extends Component implements HasForms
{
    use InteractsWithForms;
    public $markers = [];
    public $created_at = '';
    public $search = '';

    public function mount(): void
    {
        $this->form->fill();
        $this->filterAttendance();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Filter')
                    ->schema([
                        Forms\Components\DatePicker::make('created_at')
                            ->label('Tanggal')
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->created_at = $state;
                                $this->filterAttendance();
                            }),
                        Forms\Components\TextInput::make('search')
                            ->label('Cari Karyawan')
                            ->placeholder('Masukkan nama karyawan')
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state) {
                                $this->search = $state;
                                $this->filterAttendance();
                            }),
                    ]),
                
            ]);
    }

    public function render()
    {
        return view('livewire.map');
    }

    public function filterAttendance()
    {
        $query = Attendance::with('user');
        
        if ($this->created_at) {
            $query->whereDate('created_at', $this->created_at);
        }
        
        if ($this->search && strlen(trim($this->search)) > 0) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        
        $this->markers = $query->get();
        $this->dispatch('markersUpdated');
    }

    
}
