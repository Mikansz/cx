<div class="w-full">
    <!-- Search Input Section -->
    <div class="relative">
        <div class="flex gap-2">
            <div class="flex-1 relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.500ms="query"
                    placeholder="Cari lokasi (contoh: Jakarta, Bandung, Jl. Sudirman...)"
                    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
                
                <!-- Loading Spinner -->
                <div wire:loading wire:target="searchLocation" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Clear Button -->
                @if($query)
                    <button 
                        wire:click="clearSearch"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        wire:loading.remove wire:target="searchLocation"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>

            <!-- Current Location Button -->
            <button 
                wire:click="getCurrentLocation"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center gap-2"
                title="Gunakan lokasi saat ini"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="hidden sm:inline">Lokasi Saya</span>
            </button>
        </div>

        <!-- Search Results Dropdown -->
        @if($showResults && count($results) > 0)
            <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                @foreach($results as $index => $result)
                    <div 
                        wire:click="selectLocation({{ $index }})"
                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $result['display_name'] }}
                                </p>
                                @if(isset($result['address']['country']))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $result['type'] }} • {{ $result['address']['country'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- No Results Message -->
        @if($showResults && count($results) === 0 && !$isLoading && strlen($query) >= 3)
            <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg p-4">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-sm">Tidak ada lokasi ditemukan untuk "{{ $query }}"</p>
                    <p class="text-xs mt-1">Coba gunakan kata kunci yang lebih spesifik</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Selected Location Info -->
    @if($selectedLocation)
        <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        Lokasi Dipilih
                    </p>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        {{ $selectedLocation['display_name'] ?? $selectedLocation['address'] }}
                    </p>
                    @if(isset($selectedLocation['lat']) && isset($selectedLocation['lon']))
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            Koordinat: {{ number_format($selectedLocation['lat'], 6) }}, {{ number_format($selectedLocation['lon'], 6) }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Map Container -->
    <div class="mt-4">
        <div id="{{ $mapId }}" class="w-full h-64 rounded-lg border border-gray-300 dark:border-gray-600"></div>
    </div>

    <!-- JavaScript for Map and Geolocation -->
    <script>
        document.addEventListener('livewire:initialized', function() {
            let map{{ $mapId }};
            let marker{{ $mapId }};
            
            // Initialize map
            function initMap() {
                if (map{{ $mapId }}) {
                    map{{ $mapId }}.remove();
                }
                
                map{{ $mapId }} = L.map('{{ $mapId }}').setView([{{ $defaultLat }}, {{ $defaultLng }}], {{ $zoom }});
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map{{ $mapId }});
                
                // Add click event to map
                map{{ $mapId }}.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    
                    // Update marker
                    if (marker{{ $mapId }}) {
                        marker{{ $mapId }}.setLatLng([lat, lng]);
                    } else {
                        marker{{ $mapId }} = L.marker([lat, lng]).addTo(map{{ $mapId }});
                    }
                    
                    // Reverse geocoding to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            const address = data.display_name || `Lokasi (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                            @this.call('setCurrentLocation', lat, lng, address);
                        })
                        .catch(() => {
                            const address = `Lokasi (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                            @this.call('setCurrentLocation', lat, lng, address);
                        });
                });
            }
            
            // Listen for location selection
            Livewire.on('locationSelected', function(data) {
                if (data.mapId === '{{ $mapId }}') {
                    if (map{{ $mapId }}) {
                        map{{ $mapId }}.setView([data.lat, data.lng], 15);
                        
                        if (marker{{ $mapId }}) {
                            marker{{ $mapId }}.setLatLng([data.lat, data.lng]);
                        } else {
                            marker{{ $mapId }} = L.marker([data.lat, data.lng]).addTo(map{{ $mapId }});
                        }
                        
                        marker{{ $mapId }}.bindPopup(data.name).openPopup();
                    }
                }
            });
            
            // Listen for current location request
            Livewire.on('requestCurrentLocation', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Reverse geocoding
                            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                                .then(response => response.json())
                                .then(data => {
                                    const address = data.display_name || `Lokasi Saat Ini (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                                    @this.call('setCurrentLocation', lat, lng, address);
                                })
                                .catch(() => {
                                    const address = `Lokasi Saat Ini (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                                    @this.call('setCurrentLocation', lat, lng, address);
                                });
                        },
                        function(error) {
                            alert('Tidak dapat mengakses lokasi: ' + error.message);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000
                        }
                    );
                } else {
                    alert('Geolocation tidak didukung oleh browser ini.');
                }
            });
            
            // Initialize map when component loads
            setTimeout(initMap, 100);
        });
    </script>
</div>
