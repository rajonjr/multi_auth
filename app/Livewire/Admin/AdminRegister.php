<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdminRegister extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function create(): void
    {
        $this->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
            ],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => 'admin',
        ]);

        $user->sendEmailVerificationNotification();

        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        session()->flash('status', 'Admin créé. Un email de vérification a été envoyé.');
    }
    
    public function render()
    {
        return view('livewire.admin.admin-register')->layout('layouts.app');
    }
}
