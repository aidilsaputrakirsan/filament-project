<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCoursesToMataKuliah extends Migration
{
    public function up()
    {
        if (Schema::hasTable('courses') && !Schema::hasTable('mata_kuliah')) {
            Schema::rename('courses', 'mata_kuliah');
        }
        
        // Tambahkan kolom yang dibutuhkan jika belum ada
        if (Schema::hasTable('mata_kuliah')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                if (!Schema::hasColumn('mata_kuliah', 'kode')) {
                    $table->string('kode')->nullable()->after('title');
                }
                
                // Pastikan nama kolom sesuai dengan ERD
                if (Schema::hasColumn('mata_kuliah', 'description') && !Schema::hasColumn('mata_kuliah', 'description')) {
                    $table->renameColumn('description', 'description');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('mata_kuliah') && !Schema::hasTable('courses')) {
            Schema::rename('mata_kuliah', 'courses');
        }
        
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'kode')) {
                    $table->dropColumn('kode');
                }
            });
        }
    }
}