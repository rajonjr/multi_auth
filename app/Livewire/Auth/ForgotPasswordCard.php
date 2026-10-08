<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class ForgotPasswordCard extends Component
{
    public string $context = 'user';
    
    public function render()
    {
        return view('livewire.auth.forgot-password-card');
    }
}
