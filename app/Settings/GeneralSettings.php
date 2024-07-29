<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{

    public string $site_name;
    public string $site_description;
    public bool $maintenance_mode;
    public ?string $logo;

    public static function group(): string
    {
        return 'general';
    }
}