<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalAccounts = User::count();

        $usersCount = User::where('role', 'user')->count();

        $adminsCount = User::where('role', 'admin')->count();

        $verifiedUsers = User::whereNotNull('email_verified_at')->count();

        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        $twoFactorEnabled = User::whereNotNull('two_factor_secret')
            ->whereNotNull('two_factor_confirmed_at')
            ->count();

        $recentUsers = User::latest()
            ->take(6)
            ->get([
                'id',
                'name',
                'email',
                'role',
                'email_verified_at',
                'created_at',
            ]);

        $verificationRate = $totalAccounts > 0
            ? round(($verifiedUsers / $totalAccounts) * 100)
            : 0;

        $twoFactorRate = $totalAccounts > 0
            ? round(($twoFactorEnabled / $totalAccounts) * 100)
            : 0;

        return view('livewire.admin.dashboard', [
            'totalAccounts' => $totalAccounts,
            'usersCount' => $usersCount,
            'adminsCount' => $adminsCount,
            'verifiedUsers' => $verifiedUsers,
            'unverifiedUsers' => $unverifiedUsers,
            'twoFactorEnabled' => $twoFactorEnabled,
            'recentUsers' => $recentUsers,
            'verificationRate' => $verificationRate,
            'twoFactorRate' => $twoFactorRate,
        ])->layout('layouts.app');
    }
}