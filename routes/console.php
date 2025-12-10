<?php

use App\Models\Setting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// System management scheduled tasks
Schedule::command('system:health-check')->hourly();
Schedule::command('workflows:execute')->everyFifteenMinutes();
Schedule::command('backup:create')->dailyAt('02:00');
Schedule::command('search:reindex')->weekly()->sundays()->at('03:00');

// Dynamic backup schedule based on settings
Schedule::call(function () {
    $enabled = Setting::get('backup_enabled', false);
    
    if (!$enabled) {
        return;
    }
    
    Artisan::call('backup:database');
})->daily()->at('02:00');
