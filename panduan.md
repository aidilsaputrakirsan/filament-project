# Panduan Lengkap Instalasi Laravel Filament untuk Aplikasi LMS

Panduan ini mencakup instalasi dan konfigurasi Laravel Filament untuk membangun Aplikasi Learning Management System (LMS), termasuk penanganan berbagai error yang mungkin muncul dalam prosesnya.

## Daftar Isi
- [Prasyarat](#prasyarat)
- [1. Instalasi Laravel](#1-instalasi-laravel)
- [2. Instalasi Filament](#2-instalasi-filament)
- [3. Penanganan Error Ekstensi PHP](#3-penanganan-error-ekstensi-php)
- [4. Konfigurasi Database PostgreSQL](#4-konfigurasi-database-postgresql)
- [5. Menangani Masalah Izin PostgreSQL](#5-menangani-masalah-izin-postgresql)
- [6. Membuat Model dan Migrasi](#6-membuat-model-dan-migrasi)
- [7. Membuat Widget](#7-membuat-widget)
- [8. Implementasi Role dan Authentication](#8-implementasi-role-dan-authentication)
- [9. Pengembangan Lebih Lanjut](#9-pengembangan-lebih-lanjut)

## Prasyarat

Sebelum memulai, pastikan Anda memiliki:
- PHP 8.1+ dengan ekstensi yang diperlukan
- Composer
- PostgreSQL
- Node.js dan NPM

## 1. Instalasi Laravel

Buat proyek Laravel baru:

```bash
composer create-project laravel/laravel lms-filament
cd lms-filament
```

## 2. Instalasi Filament

### Menginstal Filament pada Laravel 12

Untuk Laravel 12, gunakan Filament versi 3.3.0 atau lebih baru:

```bash
composer require filament/filament:"^3.3" -W
```

Jika mengalami error seperti berikut:
```
The "3.3" constraint for "filament/filament" appears too strict and will likely not match what you want.
```

Tetap lanjutkan dan tunggu proses selesai.

### Menjalankan Installer Filament

Setelah package terinstal, jalankan installer Filament:

```bash
php artisan filament:install --panels
```

### Publikasi Asset

```bash
php artisan vendor:publish --tag=filament-config
npm install
npm run build
```

## 3. Penanganan Error Ekstensi PHP

### Error: Ekstensi intl Tidak Ditemukan

Jika Anda melihat error seperti ini:
```
filament/support v3.3.0 requires ext-intl * -> it is missing from your system. Install or enable PHP's intl extension.
```

#### Solusi:

1. Buka file `php.ini` (umumnya di `C:\php\php.ini` untuk Windows atau `/etc/php/x.x/cli/php.ini` untuk Linux)
2. Cari baris `;extension=intl`
3. Hapus tanda titik koma di depannya menjadi `extension=intl`
4. Simpan file dan restart server PHP/webserver Anda

### Error: Driver PostgreSQL Tidak Ditemukan

Jika Anda melihat error seperti berikut:
```
could not find driver (Connection: pgsql, SQL: ...)
```

#### Solusi:

1. Buka file `php.ini`
2. Cari dan aktifkan kedua ekstensi berikut:
   ```
   extension=pdo_pgsql
   extension=pgsql
   ```
3. Simpan file dan restart server PHP/webserver Anda
4. Verifikasi ekstensi sudah aktif dengan perintah:
   ```bash
   php -m | findstr pgsql
   ```

## 4. Konfigurasi Database PostgreSQL

### Setup Database di .env

Edit file `.env` dan sesuaikan konfigurasi database:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lms_filament
DB_USERNAME=filament_user
DB_PASSWORD=filament_password
```

### Membuat Database dan User

Buka PostgreSQL command line:

```bash
psql -U postgres
```

Jalankan perintah berikut:

```sql
-- Membuat database
CREATE DATABASE lms_filament;

-- Membuat user
CREATE USER filament_user WITH PASSWORD 'filament_password';

-- Memberikan hak akses pada database
GRANT ALL PRIVILEGES ON DATABASE lms_filament TO filament_user;

-- Hubungkan ke database
\c lms_filament

-- Memberikan hak akses pada schema
GRANT ALL ON SCHEMA public TO filament_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO filament_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO filament_user;
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public TO filament_user;

-- Mengatur user sebagai pemilik schema
ALTER SCHEMA public OWNER TO filament_user;

-- Keluar dari psql
\q
```

### Menjalankan Migrasi

Setelah konfigurasi database selesai, jalankan migrasi:

```bash
php artisan config:clear
php artisan migrate
```

## 5. Menangani Masalah Izin PostgreSQL

Jika Anda melihat error izin:

```
SQLSTATE[42501]: Insufficient privilege: 7 ERROR: permission denied for schema public
```

Ini berarti user PostgreSQL tidak memiliki izin yang cukup. Kembali ke langkah sebelumnya dan pastikan Anda telah memberikan semua izin yang diperlukan.

## 6. Membuat Model dan Migrasi

### Membuat Model untuk Aplikasi LMS

```bash
php artisan make:model Course -m
php artisan make:model Lesson -m
php artisan make:model Assignment -m
php artisan make:model Submission -m
php artisan make:model Enrollment -m
```

### Mengisi File Migrasi

Buat migrasi untuk menambahkan kolom role ke tabel users:

```bash
php artisan make:migration add_role_to_users_table
```

Edit file migrasi tersebut:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
```

Buat migrasi untuk tabel courses:

```php
Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pengajar
    $table->boolean('is_published')->default(false);
    $table->timestamps();
});
```

Buat migrasi untuk tabel lessons:

```php
Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content')->nullable();
    $table->foreignId('course_id')->constrained()->onDelete('cascade');
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

Buat migrasi untuk tabel assignments:

```php
Schema::create('assignments', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->foreignId('course_id')->constrained()->onDelete('cascade');
    $table->dateTime('due_date')->nullable();
    $table->integer('max_score')->default(100);
    $table->timestamps();
});
```

Buat migrasi untuk tabel submissions:

```php
Schema::create('submissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->text('content');
    $table->integer('score')->nullable();
    $table->text('feedback')->nullable();
    $table->timestamps();
});
```

Buat migrasi untuk tabel enrollments:

```php
Schema::create('enrollments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('course_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['user_id', 'course_id']);
});
```

### Menjalankan Migrasi

```bash
php artisan migrate
```

## 7. Membuat Widget

### Membuat Widget Statistik Kursus

Buat widget Filament untuk menampilkan statistik:

```bash
php artisan make:filament-widget CourseStats --stats
```

Saat diminta, pilih opsi-opsi berikut:
- Tipe widget: `1` (Stats overview)
- Resource: (kosongkan, tekan Enter)
- Lokasi widget: Pilih nama panel Anda (contoh: `aidil`)
- Contoh kode: `yes`
- Buat test: `no`

### Error Widget Tidak Ditemukan

Jika Anda melihat error:

```
Unable to find component: [Filament\Widgets\CourseStats]
```

#### Solusi:

1. **Periksa Lokasi Widget**: File widget mungkin berada di lokasi yang berbeda dari yang diharapkan.

2. **Buat Widget Secara Manual**: 

Buat file baru di `app/Filament/Widgets/CourseStats.php` (sesuaikan dengan struktur direktori Anda):

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kursus', Course::count())
                ->description('Jumlah kursus yang tersedia')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('Total Siswa', User::where('role', 'student')->count())
                ->description('Jumlah siswa terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            Stat::make('Total Pendaftaran', Enrollment::count())
                ->description('Jumlah pendaftaran kursus')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),
        ];
    }
}
```

3. **Mendaftarkan Widget di Panel Provider**:

Edit file `app/Providers/Filament/[NamaPanelAnda]PanelProvider.php`, misalnya `AidilPanelProvider.php`:

```php
->widgets([
    Widgets\AccountWidget::class,
    Widgets\FilamentInfoWidget::class,
    \App\Filament\Widgets\CourseStats::class, // Sesuaikan namespace jika perlu
])
```

## 8. Implementasi Role dan Authentication

### Membuat User Admin

Buat user admin pertama:

```bash
php artisan make:filament-user
```

Ikuti petunjuk untuk membuat user dengan email dan password.

### Mengubah Role User menjadi Admin

Gunakan tinker untuk mengubah role user menjadi admin:

```bash
php artisan tinker
```

Dalam tinker, jalankan:

```php
$user = \App\Models\User::where('email', 'email@anda.com')->first();
$user->role = 'admin';
$user->save();
exit
```

Ganti 'email@anda.com' dengan email yang Anda gunakan saat mendaftar.

### Mengimplementasikan FilamentUser

Edit file `app/Models/User.php`:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya user dengan role admin atau teacher yang bisa akses panel
        return $this->role === 'admin' || $this->role === 'teacher';
    }
    
    // Relasi dengan kursus (sebagai pengajar)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Relasi dengan enrollment
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi dengan kursus yang diikuti
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    // Relasi dengan submission
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
```

### Membuat Resource Filament untuk Course

```bash
php artisan make:filament-resource Course --generate
```

Edit file `app/Filament/Resources/CourseResource.php` sesuai kebutuhan Anda.

## 9. Pengembangan Lebih Lanjut

### Menambahkan Resource untuk Model Lain

Buat resource untuk model Lesson:

```bash
php artisan make:filament-resource Lesson --generate
```

Buat resource untuk model Assignment:

```bash
php artisan make:filament-resource Assignment --generate
```

### Menjalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses `http://127.0.0.1:8000/admin` untuk melihat panel admin Filament.

### Tips Debugging

Jika mengalami masalah, bersihkan cache:

```bash
php artisan optimize:clear
```

Untuk melihat error lebih detail, buka file `.env` dan ubah:

```
APP_DEBUG=true
```

## Kesimpulan

Dengan mengikuti panduan ini, Anda telah berhasil:
1. Menginstal Laravel dan Filament
2. Mengkonfigurasi PostgreSQL sebagai database
3. Mengatasi berbagai error umum dalam proses instalasi
4. Membuat struktur database untuk aplikasi LMS
5. Menerapkan sistem role dan authentication

Anda dapat mengembangkan aplikasi LMS ini lebih lanjut dengan menambahkan fitur-fitur seperti forum diskusi, quiz, sertifikat, pembayaran, dan analitik pembelajaran.