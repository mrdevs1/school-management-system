<?php
if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        try {
            return \App\Models\Setting::get($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('school_name')) {
    function school_name(): string
    {
        return setting('school_name', 'বিদ্যাপীঠ');
    }
}
