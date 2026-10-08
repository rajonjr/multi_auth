@php
    $isAdmin = $context === 'admin';

    $title = $isAdmin ? 'Admin Login' : 'User Login';

    $forgotUrl = $isAdmin
        ? route('admin.password.request')
        : route('password.request');

    $registerUrl = $isAdmin
        ? null
        : route('register');
@endphp

<div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
            {{ $title }}
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Connectez-vous à votre espace {{ $isAdmin ? 'administrateur' : 'utilisateur' }}.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                required
                autofocus
                autocomplete="username webauthn"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >

            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">
                Mot de passe
            </label>

            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >

            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >
                Se souvenir de moi
            </label>

            <a href="{{ $forgotUrl }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Mot de passe oublié ?
            </a>
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <span wire:loading.remove>Se connecter</span>
            <span wire:loading>Connexion en cours...</span>
        </button>

        <button
            type="button"
            onclick="loginWithPasskey()"
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
        >
            Se connecter avec une passkey
        </button>
    </form>

    @if ($registerUrl)
        <p class="mt-6 text-center text-sm text-slate-600">
            Pas encore de compte ?
            <a href="{{ $registerUrl }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                Créer un compte
            </a>
        </p>
    @endif
</div>