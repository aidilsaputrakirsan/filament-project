<!-- resources/views/filament/pages/access-denied.blade.php -->
<x-filament::page>
    <div class="flex items-center justify-center min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow-lg text-center">
            <h1 class="text-4xl font-bold text-red-600 mb-4">Akses Ditolak</h1>
            <p class="text-lg mb-6">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <p class="text-gray-600 mb-8">Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">Kembali ke Dashboard</a>
        </div>
    </div>
</x-filament::page>