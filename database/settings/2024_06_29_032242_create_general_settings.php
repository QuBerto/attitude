<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'OSRS Clan');
        $this->migrator->add('general.site_description', 'Attitude OSRS Clan');
        $this->migrator->add('general.maintenance_mode', false);
        $this->migrator->add('general.logo', false);
    }
};
