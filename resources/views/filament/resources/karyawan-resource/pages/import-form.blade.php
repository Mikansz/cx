<div>
    <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="space-y-2">
            <div class="flex items-center justify-center w-full">
                <label for="file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">XLS, XLSX, CSV (MAX. 10MB)</p>
                    </div>
                    <input id="file" name="file" type="file" class="hidden" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="updateFileName(this)" required />
                </label>
            </div>
            <div id="file-name-display" class="text-center mt-2 text-sm text-gray-600 hidden">
                <span class="font-medium"></span>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('karyawan.template') }}" class="text-primary-600 hover:underline text-sm font-medium">Download template</a>
            </div>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Penting:</strong> Pastikan format tanggal lahir menggunakan format YYYY-MM-DD (contoh: 1990-01-01)
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Import
            </button>
        </div>
    </form>
</div>

<script>
    function updateFileName(input) {
        const fileNameDisplay = document.getElementById('file-name-display');
        const fileNameSpan = fileNameDisplay.querySelector('span');
        
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            fileNameSpan.textContent = 'File terpilih: ' + fileName;
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    }
</script> 