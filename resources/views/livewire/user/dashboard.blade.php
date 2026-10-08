@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    $user = auth()->user();

    $userName = $user->name ?? 'User';
    $userInitial = strtoupper(substr($userName, 0, 1));

    $avatarUrl = $user->avatar_path
        ? Storage::disk('public')->url($user->avatar_path)
        : null;

    $emailVerified = ! is_null($user->email_verified_at);
    $twoFactorEnabled = ! is_null($user->two_factor_secret);
    $twoFactorConfirmed = ! is_null($user->two_factor_confirmed_at);

    $passkeysCount = 0;

    if (method_exists($user, 'passkeys')) {
        try {
            $passkeysCount = $user->passkeys()->count();
        } catch (\Throwable $e) {
            $passkeysCount = 0;
        }
    }

    $securityScore = 0;

    if ($emailVerified) {
        $securityScore += 35;
    }

    if ($twoFactorConfirmed) {
        $securityScore += 40;
    }

    if ($passkeysCount > 0) {
        $securityScore += 25;
    }

    $securityColor = match (true) {
        $securityScore >= 80 => 'bg-emerald-500',
        $securityScore >= 50 => 'bg-amber-500',
        default => 'bg-red-500',
    };

    $securityLabel = match (true) {
        $securityScore >= 80 => 'Excellent',
        $securityScore >= 50 => 'Moyen',
        default => 'Faible',
    };

    $dashboardUrl = Route::has('dashboard') ? route('dashboard') : '#';
    $profileUrl = Route::has('profile') ? route('profile') : '#';
    $securityUrl = Route::has('security') ? route('security') : '#';

    /*
     | Si tu n’as pas encore de route settings user,
     | le bouton Paramètres redirige vers Profile.
    */
    $settingsUrl = Route::has('settings')
        ? route('settings')
        : $profileUrl;
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: false,
        topbarDropdown: false,
        sidebarDropdown: false,
    }"
    class="min-h-screen bg-slate-100"
>
    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
    ></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col border-r border-white/10 bg-slate-950 text-white shadow-2xl transition-all duration-300 lg:sticky lg:top-0 lg:z-10 lg:h-screen lg:translate-x-0"
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                sidebarCollapsed ? 'w-20' : 'w-72'
            ]"
        >
            {{-- Brand --}}
            <div class="flex items-center justify-between border-b border-white/10 px-4 py-5">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg">
                        <x-ui.icon name="user" class="h-6 w-6" />
                    </div>

                    <div x-show="!sidebarCollapsed" x-transition class="min-w-0">
                        <h1 class="truncate text-lg font-bold">
                            User Panel
                        </h1>
                        <p class="truncate text-sm text-slate-400">
                            Espace utilisateur
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="rounded-xl p-2 text-slate-300 hover:bg-white/10 hover:text-white lg:hidden"
                >
                    <x-ui.icon name="x" class="h-5 w-5" />
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-2 overflow-y-auto px-3 py-5">
                <a
                    href="{{ $dashboardUrl }}"
                    class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white"
                >
                    <span class="flex w-7 justify-center">
                        <x-ui.icon name="dashboard" class="h-5 w-5" />
                    </span>

                    <span x-show="!sidebarCollapsed" x-transition>
                        Dashboard
                    </span>
                </a>
            </nav>

            {{-- Sidebar profile dropdown --}}
            <div class="relative border-t border-white/10 p-3" @click.outside="sidebarDropdown = false">
                <button
                    type="button"
                    @click="sidebarDropdown = ! sidebarDropdown"
                    class="flex w-full items-center gap-3 rounded-2xl bg-white/10 p-3 text-left transition hover:bg-white/15"
                >
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 font-bold text-white ring-1 ring-white/10">
                        {{ $userInitial }}
                    </div>

                    <div x-show="!sidebarCollapsed" x-transition class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">
                            {{ $userName }}
                        </p>

                        <p class="truncate text-xs text-slate-400">
                            {{ $user->email }}
                        </p>
                    </div>

                    <span x-show="!sidebarCollapsed" class="text-slate-400">
                        <x-ui.icon name="dots-vertical" class="h-5 w-5" />
                    </span>
                </button>

                <div
                    x-show="sidebarDropdown"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                    :class="sidebarCollapsed
                        ? 'left-3 w-64'
                        : 'left-3 right-3'"
                    class="absolute bottom-20 z-50 overflow-hidden rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-2xl max-lg:left-3 max-lg:right-3"
                >
                    {{-- User header --}}
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold uppercase text-white">
                                {{ $userInitial }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">
                                    {{ $userName }}
                                </p>

                                <p class="truncate text-xs text-slate-500">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="p-2">
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50"
                            >
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                    <x-ui.icon name="logout" class="h-4 w-4" />
                                </span>

                                <span class="truncate">
                                    Déconnexion
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <div class="min-w-0 flex-1">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        {{-- Mobile sidebar toggle --}}
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="rounded-xl border border-slate-300 p-2 text-slate-700 hover:bg-slate-50 lg:hidden"
                        >
                            <x-ui.icon name="menu" class="h-5 w-5" />
                        </button>

                        {{-- Desktop collapse toggle --}}
                        <button
                            type="button"
                            @click="sidebarCollapsed = ! sidebarCollapsed"
                            class="hidden rounded-xl border border-slate-300 p-2 text-slate-700 hover:bg-slate-50 lg:inline-flex"
                            title="Réduire / ouvrir la sidebar"
                        >
                            <x-ui.icon name="menu" class="h-5 w-5" />
                        </button>

                        <div class="min-w-0">
                            <h1 class="truncate text-lg font-bold text-slate-900 sm:text-xl">
                                Espace utilisateur
                            </h1>
                            <p class="truncate text-sm text-slate-500">
                                Connecté en tant que {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    {{-- Topbar dropdown --}}
                    <div class="relative" @click.outside="topbarDropdown = false">
                        <button
                            type="button"
                            @click="topbarDropdown = ! topbarDropdown"
                            class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-slate-200 bg-white p-1 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold uppercase text-white">
                                {{ $userInitial }}
                            </span>
                        </button>

                        <div
                            x-show="topbarDropdown"
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                            class="absolute right-0 z-50 mt-3 w-[calc(100vw-2rem)] max-w-xs overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:w-72"
                        >
                            {{-- Header user --}}
                            <div class="bg-slate-50 px-4 py-4 sm:px-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-base font-bold uppercase text-white sm:h-12 sm:w-12 sm:text-lg">
                                        {{ $userInitial }}
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-bold text-slate-900">
                                            {{ $userName }}
                                        </p>

                                        <p class="truncate text-xs text-slate-500">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="p-2">
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50 sm:px-4"
                                    >
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                            <x-ui.icon name="logout" class="h-4 w-4" />
                                        </span>

                                        <span class="truncate">
                                            Déconnexion
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
            </header>

            <main class="px-4 py-8 sm:px-6 lg:px-8">
                {{-- Welcome --}}
                <section class="overflow-hidden rounded-3xl bg-linear-to-br from-indigo-600 via-indigo-700 to-slate-900 p-8 text-white shadow-xl">
                    <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
                        <div>
                            <p class="text-sm font-medium text-indigo-100">
                                Bienvenue sur votre dashboard
                            </p>

                            <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                                Bonjour, {{ $userName }}
                            </h2>

                            <p class="mt-3 max-w-2xl text-sm leading-6 text-indigo-100 sm:text-base">
                                Depuis cet espace, vous pouvez gérer votre profil, consulter l’état de sécurité de votre compte,
                                activer la double authentification et gérer vos passkeys.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                            <p class="text-sm text-indigo-100">
                                Score de sécurité
                            </p>

                            <div class="mt-2 flex items-end gap-2">
                                <span class="text-4xl font-bold">
                                    {{ $securityScore }}%
                                </span>
                                <span class="pb-1 text-sm text-indigo-100">
                                    {{ $securityLabel }}
                                </span>
                            </div>

                            <div class="mt-4 h-3 w-64 overflow-hidden rounded-full bg-white/20">
                                <div
                                    class="h-full rounded-full {{ $securityColor }}"
                                    style="width: {{ $securityScore }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Stats --}}
                <section class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    {{-- Email --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Email
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $emailVerified ? 'Vérifié' : 'Non vérifié' }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $emailVerified ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                @if ($emailVerified)
                                    <x-ui.icon name="check-circle" class="h-6 w-6" />
                                @else
                                    <x-ui.icon name="mail" class="h-6 w-6" />
                                @endif
                            </div>
                        </div>

                        <p class="mt-4 text-sm text-slate-500">
                            {{ $emailVerified ? 'Votre adresse email est confirmée.' : 'Vous devez vérifier votre adresse email.' }}
                        </p>
                    </div>

                    {{-- 2FA --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Double authentification
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $twoFactorConfirmed ? 'Activée' : 'Désactivée' }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $twoFactorConfirmed ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                <x-ui.icon name="shield" class="h-6 w-6" />
                            </div>
                        </div>

                        <p class="mt-4 text-sm text-slate-500">
                            {{ $twoFactorConfirmed ? 'Votre compte est protégé par 2FA.' : 'Activez la 2FA pour plus de sécurité.' }}
                        </p>
                    </div>

                    {{-- Passkeys --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Passkeys
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $passkeysCount }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $passkeysCount > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                <x-ui.icon name="shield" class="h-6 w-6" />
                            </div>
                        </div>

                        <p class="mt-4 text-sm text-slate-500">
                            {{ $passkeysCount > 0 ? 'Vous avez au moins une passkey.' : 'Ajoutez une passkey pour une connexion rapide.' }}
                        </p>
                    </div>

                    {{-- Role --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Type de compte
                                </p>

                                <p class="mt-2 text-lg font-bold capitalize text-slate-900">
                                    {{ $user->role ?? 'user' }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                                <x-ui.icon name="user" class="h-6 w-6" />
                            </div>
                        </div>

                        <p class="mt-4 text-sm text-slate-500">
                            Compte créé le {{ $user->created_at?->format('d/m/Y') }}.
                        </p>
                    </div>
                </section>

                <section class="mt-8 grid gap-8 lg:grid-cols-3">
                    {{-- Left --}}
                    <div class="space-y-8 lg:col-span-2">
                        {{-- Quick Actions --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">
                                    Actions rapides
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Accédez rapidement aux fonctionnalités principales.
                                </p>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <a
                                    href="{{ $profileUrl }}"
                                    class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50 {{ $profileUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <x-ui.icon name="user" class="h-6 w-6" />
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-slate-900">
                                                Mon profil
                                            </h4>
                                            <p class="mt-1 text-sm text-slate-500">
                                                Image, nom, email et mot de passe.
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <a
                                    href="{{ $securityUrl }}"
                                    class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50 {{ $securityUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <x-ui.icon name="shield" class="h-6 w-6" />
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-slate-900">
                                                Sécurité du compte
                                            </h4>
                                            <p class="mt-1 text-sm text-slate-500">
                                                2FA, passkeys, confirmation.
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <a
                                    href="{{ $settingsUrl }}"
                                    class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50 {{ $settingsUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <x-ui.icon name="cog" class="h-6 w-6" />
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-slate-900">
                                                Paramètres
                                            </h4>
                                            <p class="mt-1 text-sm text-slate-500">
                                                Préférences et configuration.
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <form
                                    method="POST"
                                    action="{{ url('/logout') }}"
                                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-red-300 hover:bg-red-50"
                                >
                                    @csrf

                                    <button type="submit" class="w-full text-left">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-700">
                                                <x-ui.icon name="logout" class="h-6 w-6" />
                                            </div>

                                            <div>
                                                <h4 class="font-semibold text-slate-900">
                                                    Déconnexion
                                                </h4>
                                                <p class="mt-1 text-sm text-slate-500">
                                                    Quitter votre session.
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Security Recommendations --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                Recommandations de sécurité
                            </h3>

                            <div class="mt-6 space-y-4">
                                <div class="flex items-start gap-4 rounded-2xl border border-slate-200 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $emailVerified ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        @if ($emailVerified)
                                            <x-ui.icon name="check-circle" class="h-5 w-5" />
                                        @else
                                            <x-ui.icon name="mail" class="h-5 w-5" />
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-900">
                                            Vérification email
                                        </h4>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $emailVerified ? 'Votre adresse email est vérifiée.' : 'Vérifiez votre email pour sécuriser votre compte et récupérer votre accès.' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 rounded-2xl border border-slate-200 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $twoFactorConfirmed ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        <x-ui.icon name="shield" class="h-5 w-5" />
                                    </div>

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-900">
                                            Double authentification
                                        </h4>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $twoFactorConfirmed ? 'La double authentification est activée.' : 'Activez la 2FA pour empêcher les connexions non autorisées.' }}
                                        </p>
                                    </div>

                                    @if (! $twoFactorConfirmed)
                                        <a
                                            href="{{ $securityUrl }}"
                                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                                        >
                                            Activer
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-start gap-4 rounded-2xl border border-slate-200 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $passkeysCount > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                        <x-ui.icon name="shield" class="h-5 w-5" />
                                    </div>

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-900">
                                            Passkeys
                                        </h4>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $passkeysCount > 0 ? 'Vous avez configuré au moins une passkey.' : 'Ajoutez une passkey pour vous connecter avec Face ID, Touch ID, Windows Hello ou une clé de sécurité.' }}
                                        </p>
                                    </div>

                                    @if ($passkeysCount === 0)
                                        <a
                                            href="{{ $securityUrl }}"
                                            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                                        >
                                            Ajouter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right --}}
                    <aside class="space-y-8">
                        {{-- Profile --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div class="flex flex-col items-center text-center">
                                @if ($avatarUrl)
                                    <img
                                        src="{{ $avatarUrl }}"
                                        alt="{{ $userName }}"
                                        class="h-24 w-24 rounded-3xl object-cover shadow-lg ring-4 ring-white"
                                    >
                                @else
                                    <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-indigo-600 text-4xl font-bold text-white shadow-lg">
                                        {{ $userInitial }}
                                    </div>
                                @endif

                                <h3 class="mt-4 text-xl font-bold text-slate-900">
                                    {{ $userName }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $user->email }}
                                </p>

                                <span class="mt-4 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-700">
                                    {{ $user->role ?? 'user' }}
                                </span>
                            </div>

                            <div class="mt-6 space-y-3 border-t border-slate-200 pt-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">ID compte</span>
                                    <span class="font-medium text-slate-900">#{{ $user->id }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Créé le</span>
                                    <span class="font-medium text-slate-900">
                                        {{ $user->created_at?->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Dernière mise à jour</span>
                                    <span class="font-medium text-slate-900">
                                        {{ $user->updated_at?->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Account Health --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                État du compte
                            </h3>

                            <div class="mt-5 space-y-4">
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="font-medium text-slate-700">Sécurité</span>
                                        <span class="font-bold text-slate-900">{{ $securityScore }}%</span>
                                    </div>

                                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full {{ $securityColor }}"
                                            style="width: {{ $securityScore }}%"
                                        ></div>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-600">
                                        Niveau actuel :
                                        <span class="font-semibold text-slate-900">
                                            {{ $securityLabel }}
                                        </span>
                                    </p>

                                    @if ($securityScore < 100)
                                        <p class="mt-2 text-sm text-slate-500">
                                            Complétez les recommandations pour atteindre 100%.
                                        </p>
                                    @else
                                        <p class="mt-2 text-sm text-emerald-700">
                                            Votre compte est très bien sécurisé.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Session --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                Session actuelle
                            </h3>

                            <div class="mt-5 space-y-3 text-sm">
                                <div class="flex justify-between gap-4">
                                    <span class="text-slate-500">Adresse IP</span>
                                    <span class="font-medium text-slate-900">
                                        {{ request()->ip() }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <span class="text-slate-500">Navigateur</span>
                                    <span class="max-w-45 truncate font-medium text-slate-900">
                                        {{ request()->userAgent() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </section>
            </main>
        </div>
    </div>
</div>