<?php

namespace Database\Seeders;

use App\Models\Course;
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
            ]);
        }
        
        // Buat user pengajar jika belum ada
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'Pengajar',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]);
        }

        // Buat beberapa course
        Course::create([
            'title' => 'Kursus Laravel Dasar',
            'description' => 'Belajar dasar-dasar pemrograman dengan Laravel',
            'user_id' => $teacher->id,
            'is_published' => true,
        ]);

        Course::create([
            'title' => 'Kursus Filament Admin Panel',
            'description' => 'Belajar membuat admin panel dengan Filament',
            'user_id' => $teacher->id,
            'is_published' => true,
        ]);
    }
}