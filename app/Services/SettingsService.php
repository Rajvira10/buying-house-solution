<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function getSettings()
    {
        return Setting::first();
    }
}