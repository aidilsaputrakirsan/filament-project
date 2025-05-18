<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin jika belum ada
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'language_preference' => 'id',
                'nim_nip' => 'ADM001',
            ]);
        }
       
        // Buat user pengajar jika belum ada
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'Pengajar',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'language_preference' => 'id',
                'nim_nip' => 'DSN001',
            ]);
        }

        // Buat user mahasiswa jika belum ada
        $student = User::where('email', 'student@example.com')->first();
        if (!$student) {
            $student = User::create([
                'name' => 'Mahasiswa',
                'email' => 'student@example.com',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'language_preference' => 'id',
                'nim_nip' => 'MHS001',
            ]);
        }

        // Buat beberapa mata kuliah
        MataKuliah::create([
            'title' => 'Kursus Laravel Dasar',
            'kode' => 'KLD-001',
            'description' => 'Belajar dasar-dasar pemrograman dengan Laravel',
            'user_id' => $teacher->id,
            'is_published' => true,
        ]);

        MataKuliah::create([
            'title' => 'Kursus Filament Admin Panel',
            'kode' => 'KFA-001',
            'description' => 'Belajar membuat admin panel dengan Filament',
            'user_id' => $teacher->id,
            'is_published' => true,
        ]);
    }
}