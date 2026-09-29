<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\App;

trait LocalizationTrait
{
    /**
     * Inisialisasi bahasa saat komponen dimount
     */
    public function initializeLocalizationTrait()
    {
        $this->lang = request()->input('lang', $this->lang);
        App::setLocale($this->lang);
    }

    /**
     * Hook Livewire otomatis saat properti $lang berubah
     */
    public function updatedLang($value)
    {
        $allowedLangs = ['en', 'id', 'ja', 'zh_CN', 'ko'];

        if (!in_array($value, $allowedLangs)) {
            $value = 'en';
            $this->lang = $value;
        }

        App::setLocale($value);
    }
}
