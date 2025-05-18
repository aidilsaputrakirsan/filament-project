<?php

// app/Filament/Pages/ImportUsers.php
namespace App\Filament\Pages;

use App\Models\ImportExportLog;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportUsers extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Import Pengguna';
    protected static ?string $title = 'Import Data Pengguna';
    protected static ?string $slug = 'import-users';
    protected static string $view = 'filament.pages.import-users';
    protected static ?string $navigationGroup = 'Import/Export';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('default_role')
                    ->options([
                        'dosen' => 'Dosen',
                        'mahasiswa' => 'Mahasiswa',
                    ])
                    ->required()
                    ->label('Peran Default'),
                
                FileUpload::make('file')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                    ->directory('import-temp')
                    ->required()
                    ->label('File Excel (.xlsx)'),
            ]);
    }

    public function import(): void
    {
        $data = $this->form->getState();
        
        $filePath = Storage::disk('public')->path($data['file']);
        $defaultRole = $data['default_role'];
        
        // Pencatatan log
        $importLog = ImportExportLog::create([
            'user_id' => auth()->id(),
            'file_path' => $data['file'],
            'operation_type' => 'import',
            'status' => 'processing',
        ]);
        
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Hapus baris header
            array_shift($rows);
            
            $totalRows = count($rows);
            $successCount = 0;
            $failedCount = 0;
            $errorMessages = [];
            
            foreach ($rows as $index => $row) {
                if (count($row) < 3) continue; // Minimal nama, email, nim_nip
                
                try {
                    $userData = [
                        'name' => $row[0],
                        'email' => $row[1],
                        'nim_nip' => $row[2],
                        'role' => $defaultRole,
                        'password' => Hash::make('password123'), // Default password
                        'language_preference' => 'id',
                    ];
                    
                    // Check if user exists
                    $user = User::where('email', $userData['email'])->first();
                    
                    if ($user) {
                        // Update user
                        $user->update($userData);
                    } else {
                        // Create new user
                        User::create($userData);
                    }
                    
                    $successCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errorMessages[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            // Update log
            $importLog->update([
                'status' => $failedCount > 0 ? 'failed' : 'success',
                'records_processed' => $totalRows,
                'records_success' => $successCount,
                'records_failed' => $failedCount,
                'result_message' => $failedCount > 0 ? implode("\n", $errorMessages) : 'Import berhasil',
            ]);
            
            $this->form->fill();
            
            Notification::make()
                ->title('Import Selesai')
                ->body("Berhasil: $successCount, Gagal: $failedCount")
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Update log jika error
            $importLog->update([
                'status' => 'failed',
                'result_message' => $e->getMessage(),
            ]);
            
            Notification::make()
                ->title('Import Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}