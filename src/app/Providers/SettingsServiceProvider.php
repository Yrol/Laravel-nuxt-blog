<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     * @param \App\Setting $setting
     * @return void
     */
    public function boot(Setting $settings)
    {
        if (Schema::hasTable('settings')) {
            config()->set('settings', $settings::pluck('name', 'value')->all());
        }
    }
}
