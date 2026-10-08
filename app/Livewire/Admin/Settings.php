<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Settings extends Component
{
    public string $site_name = '';

    public string $site_description = '';

    public string $support_email = '';

    public bool $registration_enabled = true;

    public bool $email_verification_required = true;

    public bool $admin_force_2fa = false;

    public bool $maintenance_banner_enabled = false;

    public string $maintenance_banner_message = '';

    public string $default_user_role = 'user';

    public string $test_email = '';

    public function mount(): void
    {
        $this->loadSettings();

        $this->test_email = auth()->user()->email;
    }

    public function loadSettings(): void
    {
        $this->site_name = Setting::string('site_name', config('app.name'));
        $this->site_description = Setting::string('site_description', 'Plateforme Laravel Multi-Auth');
        $this->support_email = Setting::string('support_email', 'support@example.test');

        $this->registration_enabled = Setting::bool('registration_enabled', true);
        $this->email_verification_required = Setting::bool('email_verification_required', true);
        $this->admin_force_2fa = Setting::bool('admin_force_2fa', false);

        $this->maintenance_banner_enabled = Setting::bool('maintenance_banner_enabled', false);
        $this->maintenance_banner_message = Setting::string('maintenance_banner_message', 'Maintenance prévue prochainement.');

        $this->default_user_role = Setting::string('default_user_role', 'user');
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_description' => ['nullable', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'maintenance_banner_enabled' => ['boolean'],
            'maintenance_banner_message' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::setValue('site_name', $this->site_name, 'general', 'string');
        Setting::setValue('site_description', $this->site_description, 'general', 'string');
        Setting::setValue('support_email', $this->support_email, 'general', 'string');
        Setting::setValue('maintenance_banner_enabled', $this->maintenance_banner_enabled, 'general', 'boolean');
        Setting::setValue('maintenance_banner_message', $this->maintenance_banner_message, 'general', 'string');

        config(['app.name' => $this->site_name]);

        session()->flash('general_status', 'Paramètres généraux enregistrés.');
    }

    public function saveSecurity(): void
    {
        $this->validate([
            'registration_enabled' => ['boolean'],
            'email_verification_required' => ['boolean'],
            'admin_force_2fa' => ['boolean'],
            'default_user_role' => ['required', 'in:user,admin'],
        ]);

        Setting::setValue('registration_enabled', $this->registration_enabled, 'security', 'boolean');
        Setting::setValue('email_verification_required', $this->email_verification_required, 'security', 'boolean');
        Setting::setValue('admin_force_2fa', $this->admin_force_2fa, 'security', 'boolean');
        Setting::setValue('default_user_role', $this->default_user_role, 'security', 'string');

        session()->flash('security_status', 'Paramètres de sécurité enregistrés.');
    }

    public function sendTestEmail(): void
    {
        $this->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        $email = $this->test_email;

        Mail::raw(
            "Ceci est un email de test envoyé depuis les paramètres admin de ".config('app.name').".",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Email de test - '.config('app.name'));
            }
        );

        session()->flash('mail_status', 'Email de test envoyé. Vérifiez Mailpit.');
    }

    public function clearCache(): void
    {
        Artisan::call('optimize:clear');

        session()->flash('system_status', 'Cache Laravel vidé avec succès.');
    }

    public function resetDefaults(): void
    {
        Setting::setValue('site_name', 'Laravel Multi Auth', 'general', 'string');
        Setting::setValue('site_description', 'Plateforme Laravel Multi-Auth', 'general', 'string');
        Setting::setValue('support_email', 'support@example.test', 'general', 'string');

        Setting::setValue('registration_enabled', true, 'security', 'boolean');
        Setting::setValue('email_verification_required', true, 'security', 'boolean');
        Setting::setValue('admin_force_2fa', false, 'security', 'boolean');

        Setting::setValue('maintenance_banner_enabled', false, 'general', 'boolean');
        Setting::setValue('maintenance_banner_message', 'Maintenance prévue prochainement.', 'general', 'string');

        Setting::setValue('default_user_role', 'user', 'security', 'string');

        $this->loadSettings();

        session()->flash('system_status', 'Paramètres restaurés par défaut.');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.app');
    }
}
