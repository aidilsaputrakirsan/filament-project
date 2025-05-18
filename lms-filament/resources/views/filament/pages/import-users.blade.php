<!-- resources/views/filament/pages/import-users.blade.php -->
<x-filament::page>
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <h2 class="text-lg font-bold mb-4">Petunjuk Import Data Pengguna</h2>
        <p class="mb-2">1. Download template Excel untuk import pengguna dari menu Template</p>
        <p class="mb-2">2. Isi template dengan data pengguna (Nama, Email, NIM/NIP)</p>
        <p class="mb-2">3. Pilih peran default untuk semua pengguna yang akan diimport</p>
        <p class="mb-2">4. Upload file dan klik Import</p>
        <p class="mb-2">5. Password default untuk semua pengguna: <code>password123</code></p>
    </div>
    
    <form wire:submit.prevent="import">
        {{ $this->form }}
        
        <div class="mt-4">
            <x-filament::button type="submit">
                Import Pengguna
            </x-filament::button>
        </div>
    </form>
</x-filament::page>