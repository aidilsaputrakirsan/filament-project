<?php

// app/Models/LanguageTranslation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'en_translation',
        'id_translation',
        'group',
    ];
    
    public function getTranslation(string $locale): string
    {
        return $locale === 'en' ? $this->en_translation : $this->id_translation;
    }
    
    public function updateTranslation(string $locale, string $value): void
    {
        if ($locale === 'en') {
            $this->en_translation = $value;
        } else {
            $this->id_translation = $value;
        }
        $this->save();
    }
}
