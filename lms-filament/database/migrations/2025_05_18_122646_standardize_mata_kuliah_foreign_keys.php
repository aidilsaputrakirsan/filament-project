<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Pastikan tabel mata_kuliah ada (yang sebelumnya adalah courses)
        if (Schema::hasTable('courses') && !Schema::hasTable('mata_kuliah')) {
            Schema::rename('courses', 'mata_kuliah');
        }
        
        // 2. Standardisasi foreign key di tabel assignments
        if (Schema::hasTable('assignments') && Schema::hasColumn('assignments', 'course_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->renameColumn('course_id', 'mata_kuliah_id');
            });
        }
        
        // 3. Standardisasi foreign key di tabel enrollments
        if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'course_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->renameColumn('course_id', 'mata_kuliah_id');
            });
        }
        
        // 4. Standardisasi foreign key di tabel lessons
        if (Schema::hasTable('lessons') && Schema::hasColumn('lessons', 'course_id')) {
            Schema::table('lessons', function (Blueprint $table) {
                $table->renameColumn('course_id', 'mata_kuliah_id');
            });
        }
        
        // 5. Standardisasi foreign key di tabel attendances
        if (Schema::hasTable('attendances') && Schema::hasColumn('attendances', 'course_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->renameColumn('course_id', 'mata_kuliah_id');
            });
        }
        
        // 6. Standardisasi foreign key di tabel announcements
        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'course_id')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->renameColumn('course_id', 'mata_kuliah_id');
            });
        }
        
        // 7. Tambahkan kolom kode jika belum ada
        if (Schema::hasTable('mata_kuliah') && !Schema::hasColumn('mata_kuliah', 'kode')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                $table->string('kode')->nullable()->after('title');
            });
        }
    }

    public function down()
    {
        // Kembalikan ke keadaan semula jika perlu rollback
        if (Schema::hasTable('mata_kuliah')) {
            // 1. Standardisasi foreign key di tabel assignments
            if (Schema::hasTable('assignments') && Schema::hasColumn('assignments', 'mata_kuliah_id')) {
                Schema::table('assignments', function (Blueprint $table) {
                    $table->renameColumn('mata_kuliah_id', 'course_id');
                });
            }
            
            // 2. Standardisasi foreign key di tabel enrollments
            if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'mata_kuliah_id')) {
                Schema::table('enrollments', function (Blueprint $table) {
                    $table->renameColumn('mata_kuliah_id', 'course_id');
                });
            }
            
            // Dan seterusnya untuk tabel lainnya
        }
    }
};