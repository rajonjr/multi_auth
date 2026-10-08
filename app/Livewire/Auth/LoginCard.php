<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class LoginCard extends Component
{
    public string $context = 'user';

    public function render()
    {
        return view('livewire.auth.login-card');
    }
}
