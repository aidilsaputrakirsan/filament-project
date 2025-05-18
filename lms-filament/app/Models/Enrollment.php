<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'mata_kuliah_id', // Perubahan disini dari course_id
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
    
    // Alias untuk backward compatibility
    public function course()
    {
        return $this->mataKuliah();
    }
}