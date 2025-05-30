<div class="container mx-auto max-w-md py-12">
    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-red-500">
        <div class="flex items-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-800">Akses Ditolak</h2>
        </div>
        
        <div class="bg-red-50 p-4 rounded-md mb-4 border-l-4 border-red-400">
            <p class="text-red-700">{{ $errorMessage }}</p>
        </div>
        
        <p class="text-gray-600 mb-6">Anda tidak dapat mengakses halaman presensi saat ini. Silahkan hubungi administrator untuk informasi lebih lanjut.</p>
        
        <div class="flex justify-between">
            <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Kembali ke Beranda
            </a>
            
            <a href="{{ url('backoffice') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Dashboard Admin
            </a>
        </div>
    </div>
</div>