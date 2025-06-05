<div class="grid grid-cols-1 dark:bg-gray-900 md:grid-cols-12 gap-4">
    <div class="md:col-span-12 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form wire:submit.prevent="filterAttendance">
            {{ $this->form }}
        </form>
        <div class="mt-4 text-sm text-black dark:text-white font-medium">
            {{ count($markers) }} karyawan ditemukan
        </div>
    </div>
    
    <div class="md:col-span-12 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <div wire:ignore>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
            <div id="map" class="w-full" style="height: 75vh;"></div>
            <script>
            let map;
            function initializeMap() {
                if (map) {
                    map.remove();
                }

                map = L.map('map').setView([-0.089275, 121.921327], 4.5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const markers = @json($markers);
                console.log(markers);
                
                // For automatic zoom
                let leafletMarkers = [];
                let bounds = L.latLngBounds();

                markers.forEach(marker => {
                    const str = `<div style="color: black;"><strong>Nama:</strong> ${marker.user.name}<br><strong>Jam Masuk:</strong> ${marker.start_time}</div>`;
                    const lat = parseFloat(marker.start_latitude);
                    const lng = parseFloat(marker.start_longitude);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const leafletMarker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(str);
                            
                        leafletMarkers.push(leafletMarker);
                        bounds.extend([lat, lng]);
                    }
                });
                
                // Auto zoom to fit markers if there are any
                if (leafletMarkers.length > 0) {
                    map.fitBounds(bounds, {
                        padding: [50, 50],
                        maxZoom: 16
                    });
                } else {
                    // Set default view to Indonesia if no markers
                    map.setView([-2.5, 118], 5);
                }
            }

            document.addEventListener('livewire:load', function() {
                initializeMap();
            });

            document.addEventListener('livewire:initialized', function() {
                Livewire.on('markersUpdated', function() {
                    initializeMap();
                });
            });
            </script>
        </div>
    </div>
</div>
