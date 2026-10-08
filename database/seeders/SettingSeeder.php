<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::setValue('site_name', config('app.name', 'Laravel'), 'general', 'string');
        Setting::setValue('site_description', 'Plateforme Laravel Multi-Auth', 'general', 'string');
        Setting::setValue('support_email', 'support@example.test', 'general', 'string');

        Setting::setValue('registration_enabled', true, 'security', 'boolean');
        Setting::setValue('email_verification_required', true, 'security', 'boolean');
        Setting::setValue('admin_force_2fa', false, 'security', 'boolean');

        Setting::setValue('maintenance_banner_enabled', false, 'general', 'boolean');
        Setting::setValue('maintenance_banner_message', 'Maintenance prévue prochainement.', 'general', 'string');

        Setting::setValue('default_user_role', 'user', 'security', 'string');
    }
}
