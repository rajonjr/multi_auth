<?php

namespace App\Livewire\Auth;

use Illuminate\Http\Request;
use Livewire\Component;

class ResetPasswordCard extends Component
{
    public string $token = '';

    public string $email = '';

    public function mount(Request $request): void
    {
        $this->token = $request->route('token');
        $this->email = $request->query('email', '');
    }

    public function render()
    {
        return view('livewire.auth.reset-password-card');
    }
}
