<?php

namespace App\Livewire;

use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

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
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->created_at = $state;
                                $this->filterAttendance();
                            }),
                        Forms\Components\TextInput::make('search')
                            ->label('Cari Karyawan')
                            ->placeholder('Masukkan nama karyawan')
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state) {
                                $this->search = $state;
                                $this->filterAttendance();
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public function render()
    {
        return view('livewire.map');
    }

    public function filterAttendance()
    {
        $query = Attendance::with('user')
            ->whereNotNull('start_latitude')
            ->whereNotNull('start_longitude');

        if ($this->created_at) {
            $query->whereDate('created_at', $this->created_at);
        }

        if ($this->search && strlen(trim($this->search)) > 0) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $this->markers = $query->orderBy('created_at', 'desc')->get();
        $this->dispatch('markersUpdated');
    }
}
