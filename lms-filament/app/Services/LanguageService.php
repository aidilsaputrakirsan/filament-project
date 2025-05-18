<?php

// app/Services/LanguageService.php
namespace App\Services;

use App\Models\LanguageTranslation;

class LanguageService
{
    public function getAvailableLocales(): array
    {
        return ['id', 'en'];
    }
    
    public function getCurrentLocale(): string
    {
        return app()->getLocale();
    }
    
    public function setLocale(string $locale): void
    {
        if (!in_array($locale, $this->getAvailableLocales())) {
            $locale = 'id';
        }
        
        app()->setLocale($locale);
        
        if (auth()->check()) {
            auth()->user()->setLanguagePreference($locale);
        } else {
            session()->put('locale', $locale);
        }
    }
    
    public function translate(string $key, array $params = [], ?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        $translation = LanguageTranslation::where('key', $key)->first();
        
        if (!$translation) {
            return $key;
        }
        
        $text = $translation->getTranslation($locale);
        
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $text = str_replace(":$param", $value, $text);
            }
        }
        
        return $text;
    }
    
    public function exportTranslations(string $locale): array
    {
        $translations = [];
        $translationRecords = LanguageTranslation::all();
        
        foreach ($translationRecords as $record) {
            $translations[$record->group][$record->key] = $record->getTranslation($locale);
        }
        
        return $translations;
    }
    
    public function importTranslations(string $locale, array $data): void
    {
        foreach ($data as $group => $translations) {
            foreach ($translations as $key => $value) {
                $record = LanguageTranslation::firstOrNew([
                    'key' => $key,
                    'group' => $group,
                ]);
                
                if ($locale === 'en') {
                    $record->en_translation = $value;
                } else {
                    $record->id_translation = $value;
                }
                
                $record->save();
            }
        }
    }
}