<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LocationSearch extends Component
{
    public $query = '';
    public $results = [];
    public $selectedLocation = null;
    public $showResults = false;
    public $isLoading = false;
    public $currentLocation = null;
    public $mapId = 'location-search-map';
    
    // Map settings
    public $defaultLat = -6.2088;
    public $defaultLng = 106.8456;
    public $zoom = 13;

    protected $listeners = ['locationSelected', 'getCurrentLocation'];

    public function mount($mapId = null)
    {
        if ($mapId) {
            $this->mapId = $mapId;
        }
    }

    public function updatedQuery()
    {
        if (strlen($this->query) < 3) {
            $this->results = [];
            $this->showResults = false;
            return;
        }

        $this->searchLocation();
    }

    public function searchLocation()
    {
        $this->isLoading = true;
        
        try {
            // Menggunakan Nominatim API (OpenStreetMap)
            $response = Http::timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $this->query,
                'format' => 'json',
                'limit' => 10,
                'countrycodes' => 'id', // Fokus ke Indonesia
                'addressdetails' => 1,
                'extratags' => 1,
            ]);

            if ($response->successful()) {
                $this->results = collect($response->json())->map(function ($item) {
                    return [
                        'place_id' => $item['place_id'] ?? '',
                        'display_name' => $item['display_name'] ?? '',
                        'lat' => floatval($item['lat'] ?? 0),
                        'lon' => floatval($item['lon'] ?? 0),
                        'type' => $item['type'] ?? '',
                        'importance' => $item['importance'] ?? 0,
                        'address' => $item['address'] ?? [],
                    ];
                })->sortByDesc('importance')->take(8)->values()->toArray();

                $this->showResults = true;
            } else {
                $this->results = [];
                $this->showResults = false;
            }
        } catch (\Exception $e) {
            $this->results = [];
            $this->showResults = false;
            session()->flash('error', 'Gagal mencari lokasi: ' . $e->getMessage());
        }

        $this->isLoading = false;
    }

    public function selectLocation($index)
    {
        if (isset($this->results[$index])) {
            $location = $this->results[$index];
            $this->selectedLocation = $location;
            $this->query = $location['display_name'];
            $this->showResults = false;
            
            // Emit event untuk update peta
            $this->dispatch('locationSelected', [
                'lat' => $location['lat'],
                'lng' => $location['lon'],
                'name' => $location['display_name'],
                'mapId' => $this->mapId
            ]);
        }
    }

    public function getCurrentLocation()
    {
        // Ini akan ditangani oleh JavaScript
        $this->dispatch('requestCurrentLocation');
    }

    public function setCurrentLocation($lat, $lng, $address = null)
    {
        $this->currentLocation = [
            'lat' => $lat,
            'lng' => $lng,
            'address' => $address ?? "Lokasi Saat Ini ({$lat}, {$lng})"
        ];

        $this->query = $this->currentLocation['address'];
        $this->selectedLocation = $this->currentLocation;
        
        $this->dispatch('locationSelected', [
            'lat' => $lat,
            'lng' => $lng,
            'name' => $this->currentLocation['address'],
            'mapId' => $this->mapId
        ]);
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->results = [];
        $this->showResults = false;
        $this->selectedLocation = null;
    }

    public function render()
    {
        return view('livewire.location-search');
    }
}
