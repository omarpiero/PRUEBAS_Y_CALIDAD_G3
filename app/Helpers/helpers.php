<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /**
     * Get a setting value cached forever.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            return \App\Models\Setting::get($key, $default);
        });
    }
}
