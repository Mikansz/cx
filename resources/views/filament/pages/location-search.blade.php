<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">
                        Pencarian Lokasi
                    </h2>
                    <p class="text-blue-100 mt-1">
                        Temukan lokasi dengan mudah menggunakan pencarian atau klik pada peta
                    </p>
                </div>
                <div class="text-right">
                    <svg class="w-16 h-16 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Instructions Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Cara Menggunakan Pencarian Lokasi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Ketik Lokasi</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan nama tempat, alamat, atau landmark yang ingin dicari</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <span class="text-green-600 dark:text-green-400 font-semibold text-sm">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Pilih dari Hasil</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Klik pada hasil pencarian yang sesuai atau gunakan lokasi saat ini</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 dark:text-purple-400 font-semibold text-sm">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Klik Peta</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Atau klik langsung pada peta untuk menandai lokasi tertentu</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Location Search Component --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Pencarian Lokasi Interaktif
            </h3>
            @livewire('location-search', ['mapId' => 'main-location-search'])
        </div>

        {{-- Quick Location Shortcuts --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Lokasi Populer
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @php
                $popularLocations = [
                    ['name' => 'Jakarta', 'lat' => -6.2088, 'lng' => 106.8456],
                    ['name' => 'Bandung', 'lat' => -6.9175, 'lng' => 107.6191],
                    ['name' => 'Surabaya', 'lat' => -7.2575, 'lng' => 112.7521],
                    ['name' => 'Yogyakarta', 'lat' => -7.7956, 'lng' => 110.3695],
                    ['name' => 'Medan', 'lat' => 3.5952, 'lng' => 98.6722],
                    ['name' => 'Semarang', 'lat' => -6.9667, 'lng' => 110.4167],
                ];
                @endphp
                
                @foreach($popularLocations as $location)
                    <button 
                        onclick="selectQuickLocation('{{ $location['name'] }}', {{ $location['lat'] }}, {{ $location['lng'] }})"
                        class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 text-center"
                    >
                        {{ $location['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Tips and Information --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="flex-shrink-0 w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <div>
                        <h4 class="font-medium text-yellow-800 dark:text-yellow-200">Tips Pencarian</h4>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 mt-2 space-y-1">
                            <li>• Gunakan nama lengkap tempat untuk hasil yang lebih akurat</li>
                            <li>• Tambahkan nama kota atau provinsi untuk mempersempit pencarian</li>
                            <li>• Gunakan landmark terkenal sebagai referensi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="flex-shrink-0 w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 002 12a10 10 0 0010 10 10 10 0 0010-10A10 10 0 0012 2z"/>
                    </svg>
                    <div>
                        <h4 class="font-medium text-blue-800 dark:text-blue-200">Informasi</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 mt-2 space-y-1">
                            <li>• Data lokasi menggunakan OpenStreetMap</li>
                            <li>• Koordinat ditampilkan dalam format desimal</li>
                            <li>• Klik peta untuk mendapatkan koordinat tepat</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Quick Location Selection --}}
    <script>
        function selectQuickLocation(name, lat, lng) {
            // Dispatch event to the Livewire component
            Livewire.dispatch('locationSelected', {
                lat: lat,
                lng: lng,
                name: name,
                mapId: 'main-location-search'
            });
            
            // Update the search input
            const searchComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
            if (searchComponent) {
                searchComponent.set('query', name);
                searchComponent.set('selectedLocation', {
                    display_name: name,
                    lat: lat,
                    lon: lng
                });
            }
        }
    </script>

    {{-- Include Leaflet CSS and JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</x-filament-panels::page>
