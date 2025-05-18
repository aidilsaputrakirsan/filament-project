<?php

// app/Filament/Pages/Profile.php
namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Profile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profil';
    protected static ?string $title = 'Profil Pengguna';
    protected static ?string $slug = 'profile';
    protected static string $view = 'filament.pages.profile';

    public ?array $data = [];
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'nim_nip' => $this->user->nim_nip,
            'profile_photo' => $this->user->profile_photo,
            'language_preference' => $this->user->language_preference,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),
                
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('nim_nip')
                    ->label('NIM/NIP')
                    ->maxLength(50),
                
                FileUpload::make('profile_photo')
                    ->image()
                    ->directory('profile-photos')
                    ->label('Foto Profil'),
                
                Select::make('language_preference')
                    ->options([
                        'id' => 'Bahasa Indonesia',
                        'en' => 'English',
                    ])
                    ->default('id')
                    ->label('Preferensi Bahasa'),
                
                TextInput::make('password')
                    ->password()
                    ->label('Password Baru (kosongkan jika tidak diubah)')
                    ->dehydrated(false),
                
                TextInput::make('password_confirmation')
                    ->password()
                    ->label('Konfirmasi Password Baru')
                    ->dehydrated(false),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->nim_nip = $data['nim_nip'];
        $this->user->profile_photo = $data['profile_photo'];
        $this->user->language_preference = $data['language_preference'];
        
        if (!empty($data['password'])) {
            if ($data['password'] === $data['password_confirmation']) {
                $this->user->password = Hash::make($data['password']);
            } else {
                Notification::make()
                    ->title('Password tidak cocok')
                    ->danger()
                    ->send();
                return;
            }
        }
        
        $this->user->save();
        
        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }
}
