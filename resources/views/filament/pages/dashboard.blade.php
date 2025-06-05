<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">
                        Selamat datang, {{ auth()->user()->name }}!
                    </h2>
                    <p class="text-primary-100 mt-1">
                        {{ $this->getSubheading() }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-primary-100 text-sm">{{ now()->format('l, d F Y') }}</p>
                    <p class="text-primary-100 text-sm">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- Role-specific Quick Actions --}}
        @if(auth()->user()->hasRole('karyawan'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('presensi') }}" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <x-heroicon-o-clock class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Presensi</h3>
                            <p class="text-sm text-gray-600">Absen masuk/keluar</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/permission-requests/create" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <x-heroicon-o-calendar-days class="w-6 h-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Ajukan Cuti</h3>
                            <p class="text-sm text-gray-600">Buat permintaan cuti</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/penggajian" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <x-heroicon-o-banknotes class="w-6 h-6 text-yellow-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Slip Gaji</h3>
                            <p class="text-sm text-gray-600">Lihat riwayat gaji</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->user()->hasRole('hrd'))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="/backoffice/karyawans" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <x-heroicon-o-user-group class="w-6 h-6 text-purple-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Kelola Karyawan</h3>
                            <p class="text-sm text-gray-600">Data karyawan</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/attendances" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <x-heroicon-o-clock class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Kehadiran</h3>
                            <p class="text-sm text-gray-600">Monitor absensi</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/leave-requests" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <x-heroicon-o-calendar-days class="w-6 h-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Cuti Karyawan</h3>
                            <p class="text-sm text-gray-600">Kelola permintaan</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/jabatans" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <x-heroicon-o-briefcase class="w-6 h-6 text-indigo-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Jabatan</h3>
                            <p class="text-sm text-gray-600">Kelola posisi</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->user()->hasRole('cfo'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="/backoffice/penggajian" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <x-heroicon-o-banknotes class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Penggajian</h3>
                            <p class="text-sm text-gray-600">Kelola gaji karyawan</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/karyawans" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <x-heroicon-o-user-group class="w-6 h-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Data Karyawan</h3>
                            <p class="text-sm text-gray-600">Lihat info karyawan</p>
                        </div>
                    </div>
                </a>
                
                <a href="/backoffice/jabatans" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-purple-600" />
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Laporan Keuangan</h3>
                            <p class="text-sm text-gray-600">Analisis finansial</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        {{-- Widgets Section --}}
        <div>
            {{ $this->getWidgetsData() }}
        </div>
    </div>
</x-filament-panels::page>
