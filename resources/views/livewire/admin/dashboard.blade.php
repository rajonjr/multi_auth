@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    $admin = auth()->user();

    $adminName = $admin->name ?? 'Admin';
    $adminInitial = strtoupper(substr($adminName, 0, 1));

    // $adminAvatarUrl = $admin->avatar_path
    //     ? Storage::disk('public')->url($admin->avatar_path)
    //     : null;

    $emailVerified = ! is_null($admin->email_verified_at);
    $twoFactorActive = ! is_null($admin->two_factor_secret) && ! is_null($admin->two_factor_confirmed_at);

    $totalAccounts = $totalAccounts ?? 0;
    $usersCount = $usersCount ?? 0;
    $adminsCount = $adminsCount ?? 0;
    $verifiedUsers = $verifiedUsers ?? 0;
    $unverifiedUsers = $unverifiedUsers ?? 0;
    $twoFactorEnabled = $twoFactorEnabled ?? 0;
    $verificationRate = $verificationRate ?? 0;
    $twoFactorRate = $twoFactorRate ?? 0;
    $recentUsers = $recentUsers ?? collect();

    $securityScore = 0;

    if ($emailVerified) {
        $securityScore += 40;
    }

    if ($twoFactorActive) {
        $securityScore += 40;
    }

    if (($admin->role ?? null) === 'admin') {
        $securityScore += 20;
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

    $profileUrl = Route::has('profile') ? route('profile') : '#';
    $securityUrl = Route::has('security') ? route('security') : '#';
    $settingsUrl = Route::has('admin.settings') ? route('admin.settings') : '#';
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
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-lg font-bold shadow-lg">
                        A
                    </div>

                    <div x-show="!sidebarCollapsed" x-transition class="min-w-0">
                        <h1 class="truncate text-lg font-bold">
                            Admin Panel
                        </h1>
                        <p class="truncate text-sm text-slate-400">
                            Gestion plateforme
                        </p>
                    </div>
                </div>

                {{-- Close mobile --}}
                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="rounded-xl p-2 text-slate-300 hover:bg-white/10 hover:text-white lg:hidden"
                >
                    ✕
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-2 overflow-y-auto px-3 py-5">
                <a
                    href="{{ route('admin.dashboard') }}"
                    title="Dashboard"
                    class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white"
                >
                    <span class="flex w-7 justify-center">
                        <x-ui.icon name="dashboard" class="h-5 w-5" />
                    </span>
                    <span x-show="!sidebarCollapsed" x-transition>
                        Dashboard
                    </span>
                </a>

                @if (Route::has('admin.register'))
                    <a
                        href="{{ route('admin.register') }}"
                        title="Créer un admin"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white"
                    >
                        <span class="flex w-7 justify-center">
                            <x-ui.icon name="user-plus" class="h-5 w-5" />
                        </span>
                        <span x-show="!sidebarCollapsed" x-transition>
                            Créer un admin
                        </span>
                    </a>
                @endif

                @if (Route::has('security'))
                    <a
                        href="{{ route('security') }}"
                        title="Sécurité"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white"
                    >
                        <span class="flex w-7 justify-center">
                            <x-ui.icon name="shield" class="h-5 w-5" />
                        </span>
                        <span x-show="!sidebarCollapsed" x-transition>
                            Sécurité
                        </span>
                    </a>
                @endif

                <a
                    href="{{ $profileUrl }}"
                    title="Profil"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white {{ $profileUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                >
                    <span class="flex w-7 justify-center">
                        <x-ui.icon name="user" class="h-5 w-5" />
                    </span>
                    <span x-show="!sidebarCollapsed" x-transition>
                        Profil
                    </span>
                </a>

                <a
                    href="{{ $settingsUrl }}"
                    title="Paramètres"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white {{ $settingsUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                >
                    <span class="flex w-7 justify-center">
                        <x-ui.icon name="cog" class="h-5 w-5" />
                    </span>
                    <span x-show="!sidebarCollapsed" x-transition>
                        Paramètres
                    </span>
                </a>

                <div class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-500">
                    <span class="flex w-7 justify-center">
                        <x-ui.icon name="users" class="h-5 w-5" />
                    </span>
                    <span x-show="!sidebarCollapsed" x-transition>
                        Utilisateurs
                    </span>
                </div>

                <div class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-500">
                    <span class="flex w-7 justify-center">
                        <x-ui.icon name="document" class="h-5 w-5" />
                    </span>
                    <span x-show="!sidebarCollapsed" x-transition>
                        Logs
                    </span>
                </div>
            </nav>

            {{-- Sidebar profile dropdown --}}
            <div class="relative border-t border-white/10 p-3" @click.outside="sidebarDropdown = false">
                <button
                    type="button"
                    @click="sidebarDropdown = ! sidebarDropdown"
                    class="flex w-full items-center gap-3 rounded-2xl bg-white/10 p-3 text-left transition hover:bg-white/15"
                >
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white">
                        {{ $adminInitial }}
                    </div>

                    <div x-show="!sidebarCollapsed" x-transition class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">
                            {{ $adminName }}
                        </p>
                        <p class="truncate text-xs text-slate-400">
                            {{ $admin->email }}
                        </p>
                    </div>

                    <span x-show="!sidebarCollapsed" class="text-slate-400">
                        ⋮
                    </span>
                </button>

                <div
                    x-show="sidebarDropdown"
                    x-cloak
                    x-transition
                    class="absolute bottom-20 left-3 right-3 overflow-hidden rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-2xl"
                >
                    <div class="border-b border-slate-200 px-4 py-4">
                        <p class="truncate text-sm font-semibold text-slate-900">
                            {{ $adminName }}
                        </p>
                        <p class="truncate text-xs text-slate-500">
                            {{ $admin->email }}
                        </p>
                    </div>

                    <div class="p-2">
                        <a
                            href="{{ $profileUrl }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ $profileUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                        >
                            <x-ui.icon name="user" class="h-5 w-5" />
                            Profil
                        </a>

                        <a
                            href="{{ $securityUrl }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ $securityUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                        >
                            <x-ui.icon name="shield" class="h-5 w-5" />
                            Sécurité
                        </a>

                        <a
                            href="{{ $settingsUrl }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ $settingsUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                        >  
                            <x-ui.icon name="cog" class="h-5 w-5" />
                            Paramètres
                        </a>

                        <form method="POST" action="{{ url('/logout') }}" class="mt-2 border-t border-slate-200 pt-2">
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                            >
                                <x-ui.icon name="logout" class="h-5 w-5" />
                                Déconnexion
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
                            <h2 class="truncate text-lg font-bold text-slate-900 sm:text-xl">
                                Dashboard administrateur
                            </h2>
                            <p class="truncate text-sm text-slate-500">
                                Bienvenue {{ $adminName }}, voici un résumé de votre plateforme.
                            </p>
                        </div>
                    </div>

                    {{-- Topbar profile dropdown --}}
                    <div class="relative" @click.outside="topbarDropdown = false">
                        <button
                            type="button"
                            @click="topbarDropdown = ! topbarDropdown"
                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm transition hover:bg-slate-50"
                        >
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white">
                                {{ $adminInitial }}
                            </div>

                            <div class="hidden text-left sm:block">
                                <p class="max-w-40 truncate text-sm font-semibold text-slate-900">
                                    {{ $adminName }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Administrateur
                                </p>
                            </div>

                            <span class="text-slate-400">
                                <x-ui.icon name="chevron-down" class="h-5 w-5" />
                            </span>
                        </button>

                        <div
                            x-show="topbarDropdown"
                            x-cloak
                            x-transition
                            class="absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                        >
                            <div class="bg-slate-50 px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 font-bold text-white">
                                        {{ $adminInitial }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">
                                            {{ $adminName }}
                                        </p>
                                        <p class="truncate text-xs text-slate-500">
                                            {{ $admin->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2">
                                <a
                                    href="{{ $profileUrl }}"
                                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ $profileUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <span><x-ui.icon name="user" class="h-5 w-5" /></span>
                                    <span>Mon profil</span>
                                </a>

                                <a
                                    href="{{ $securityUrl }}"
                                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ $securityUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <span><x-ui.icon name="shield" class="h-5 w-5" /></span>
                                    <span>Sécurité</span>
                                </a>

                                <a
                                    href="{{ $settingsUrl }}"
                                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ $settingsUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <span><x-ui.icon name="cog" class="h-5 w-5" /></span>
                                    <span>Paramètres</span>
                                </a>

                                <form method="POST" action="{{ url('/logout') }}" class="mt-2 border-t border-slate-200 pt-2">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                                    >
                                        <span><x-ui.icon name="logout" class="h-5 w-5" /></span>
                                        <span>Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-4 py-8 sm:px-6 lg:px-8">
                {{-- Hero --}}
                <section class="overflow-hidden rounded-3xl bg-linear-to-br from-slate-950 via-indigo-950 to-indigo-700 p-8 text-white shadow-xl">
                    <div class="flex flex-col justify-between gap-8 xl:flex-row xl:items-center">
                        <div>
                            <p class="text-sm font-medium text-indigo-200">
                                Vue globale administrateur
                            </p>

                            <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                                Admin Dashboard
                            </h1>

                            <p class="mt-4 max-w-3xl text-sm leading-6 text-indigo-100 sm:text-base">
                                Gérez les comptes, surveillez la sécurité, consultez les nouveaux utilisateurs
                                et contrôlez les accès administrateur depuis un espace centralisé.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                                <p class="text-sm text-indigo-100">
                                    Sécurité admin
                                </p>

                                <div class="mt-2 flex items-end gap-2">
                                    <span class="text-4xl font-bold">
                                        {{ $securityScore }}%
                                    </span>
                                    <span class="pb-1 text-sm text-indigo-100">
                                        {{ $securityLabel }}
                                    </span>
                                </div>

                                <div class="mt-4 h-3 overflow-hidden rounded-full bg-white/20">
                                    <div
                                        class="h-full rounded-full {{ $securityColor }}"
                                        style="width: {{ $securityScore }}%"
                                    ></div>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                                <p class="text-sm text-indigo-100">
                                    Comptes totaux
                                </p>

                                <p class="mt-2 text-4xl font-bold">
                                    {{ $totalAccounts }}
                                </p>

                                <p class="mt-4 text-sm text-indigo-100">
                                    {{ $usersCount }} utilisateurs · {{ $adminsCount }} admins
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Stats --}}
                <section class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Comptes totaux
                                </p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">
                                    {{ $totalAccounts }}
                                </p>
                            </div>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-2xl text-indigo-700">
                                <x-ui.icon name="users" class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">
                            Tous les comptes enregistrés.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Utilisateurs
                                </p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">
                                    {{ $usersCount }}
                                </p>
                            </div>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-2xl text-emerald-700">
                                <x-ui.icon name="user" class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">
                            Comptes avec le rôle user.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Administrateurs
                                </p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">
                                    {{ $adminsCount }}
                                </p>
                            </div>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-2xl text-purple-700">
                                <span class="flex w-7 justify-center">
                                    <x-ui.icon name="user-plus" class="h-5 w-5" />
                                </span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">
                            Comptes avec le rôle admin.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Emails non vérifiés
                                </p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">
                                    {{ $unverifiedUsers }}
                                </p>
                            </div>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-2xl text-amber-700">
                                ✉️
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">
                            Comptes à vérifier.
                        </p>
                    </div>
                </section>

                {{-- Main grid --}}
                <section class="mt-8 grid gap-8 xl:grid-cols-3">
                    {{-- Left --}}
                    <div class="space-y-8 xl:col-span-2">
                        {{-- Quick actions --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">
                                    Actions rapides
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Raccourcis utiles pour gérer la plateforme.
                                </p>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                @if (Route::has('admin.register'))
                                    <a
                                        href="{{ route('admin.register') }}"
                                        class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-xl text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                                <x-ui.icon name="admin" class="h-5 w-5" />
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-slate-900">
                                                    Créer un administrateur
                                                </h4>
                                                <p class="mt-1 text-sm text-slate-500">
                                                    Ajouter un nouveau compte admin.
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                <a
                                    href="{{ $securityUrl }}"
                                    class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50 {{ $securityUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-xl text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <x-ui.icon name="shield" class="h-5 w-5" />
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
                                    href="{{ $profileUrl }}"
                                    class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50 {{ $profileUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-xl text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <x-ui.icon name="user" class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-900">
                                                Profil
                                            </h4>
                                            <p class="mt-1 text-sm text-slate-500">
                                                Modifier les informations du compte.
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <a
                                    href="{{ $settingsUrl }}"
                                    class="group rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50 {{ $settingsUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-xl text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <x-ui.icon name="cog" class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-900">
                                                Paramètres
                                            </h4>
                                            <p class="mt-1 text-sm text-slate-500">
                                                Configuration administrateur.
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        {{-- Recent users --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">
                                    Derniers comptes créés
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Les derniers utilisateurs inscrits sur la plateforme.
                                </p>
                            </div>

                            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Utilisateur
                                                </th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Rôle
                                                </th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Email
                                                </th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Créé le
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-slate-200 bg-white">
                                            @forelse ($recentUsers as $user)
                                                <tr class="hover:bg-slate-50">
                                                    <td class="px-5 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>

                                                            <div>
                                                                <p class="font-semibold text-slate-900">
                                                                    {{ $user->name }}
                                                                </p>
                                                                <p class="text-xs text-slate-500">
                                                                    #{{ $user->id }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <span
                                                            class="rounded-full px-3 py-1 text-xs font-semibold capitalize
                                                            {{ $user->role === 'admin'
                                                                ? 'bg-purple-100 text-purple-700'
                                                                : 'bg-emerald-100 text-emerald-700' }}"
                                                        >
                                                            {{ $user->role }}
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <p class="text-sm font-medium text-slate-900">
                                                            {{ $user->email }}
                                                        </p>

                                                        @if ($user->email_verified_at)
                                                            <p class="mt-1 text-xs text-emerald-600">
                                                                Vérifié
                                                            </p>
                                                        @else
                                                            <p class="mt-1 text-xs text-amber-600">
                                                                Non vérifié
                                                            </p>
                                                        @endif
                                                    </td>

                                                    <td class="px-5 py-4 text-sm text-slate-500">
                                                        {{ $user->created_at?->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500">
                                                        Aucun utilisateur trouvé.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Security overview --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                Sécurité globale
                            </h3>

                            <div class="mt-6 grid gap-5 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200 p-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">
                                                Emails vérifiés
                                            </p>
                                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                                {{ $verificationRate }}%
                                            </p>
                                        </div>

                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                            ✓
                                        </div>
                                    </div>

                                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full bg-emerald-500"
                                            style="width: {{ $verificationRate }}%"
                                        ></div>
                                    </div>

                                    <p class="mt-3 text-sm text-slate-500">
                                        {{ $verifiedUsers }} comptes vérifiés sur {{ $totalAccounts }}.
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 p-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">
                                                2FA activée
                                            </p>
                                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                                {{ $twoFactorRate }}%
                                            </p>
                                        </div>

                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                                            <x-ui.icon name="shield" class="h-5 w-5" />
                                        </div>
                                    </div>

                                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full bg-indigo-500"
                                            style="width: {{ $twoFactorRate }}%"
                                        ></div>
                                    </div>

                                    <p class="mt-3 text-sm text-slate-500">
                                        {{ $twoFactorEnabled }} comptes avec 2FA confirmée.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right --}}
                    <aside class="space-y-8">
                        {{-- Admin profile --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-indigo-600 text-4xl font-bold text-white shadow-lg">
                                    {{ $adminInitial }}
                                </div>

                                <h3 class="mt-4 text-xl font-bold text-slate-900">
                                    {{ $adminName }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $admin->email }}
                                </p>

                                <span class="mt-4 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-purple-700">
                                    {{ $admin->role ?? 'admin' }}
                                </span>
                            </div>

                            <div class="mt-6 space-y-3 border-t border-slate-200 pt-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">ID</span>
                                    <span class="font-medium text-slate-900">#{{ $admin->id }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Email</span>
                                    <span class="font-medium {{ $emailVerified ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $emailVerified ? 'Vérifié' : 'Non vérifié' }}
                                    </span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">2FA</span>
                                    <span class="font-medium {{ $twoFactorActive ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $twoFactorActive ? 'Activée' : 'Désactivée' }}
                                    </span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Créé le</span>
                                    <span class="font-medium text-slate-900">
                                        {{ $admin->created_at?->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Admin security --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                Sécurité admin
                            </h3>

                            <div class="mt-5">
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-700">
                                        Score
                                    </span>
                                    <span class="font-bold text-slate-900">
                                        {{ $securityScore }}%
                                    </span>
                                </div>

                                <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        class="h-full rounded-full {{ $securityColor }}"
                                        style="width: {{ $securityScore }}%"
                                    ></div>
                                </div>

                                <div class="mt-4 rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-600">
                                        Niveau actuel :
                                        <span class="font-semibold text-slate-900">
                                            {{ $securityLabel }}
                                        </span>
                                    </p>

                                    @if (! $twoFactorActive)
                                        <p class="mt-2 text-sm text-amber-700">
                                            Activez la 2FA pour mieux protéger votre compte admin.
                                        </p>
                                    @else
                                        <p class="mt-2 text-sm text-emerald-700">
                                            Votre compte admin est bien protégé.
                                        </p>
                                    @endif
                                </div>

                                <a
                                    href="{{ $securityUrl }}"
                                    class="mt-4 inline-flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-500 {{ $securityUrl === '#' ? 'pointer-events-none opacity-50' : '' }}"
                                >
                                    Gérer ma sécurité
                                </a>
                            </div>
                        </div>

                        {{-- System --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                État système
                            </h3>

                            <div class="mt-5 space-y-4">
                                <div class="flex items-center justify-between rounded-2xl bg-emerald-50 p-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            Application
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Statut général
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Online
                                    </span>
                                </div>

                                <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            Environnement
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            APP_ENV
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ app()->environment() }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            Date serveur
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Heure actuelle
                                        </p>
                                    </div>

                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ now()->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Current session --}}
                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="text-lg font-bold text-slate-900">
                                Session actuelle
                            </h3>

                            <div class="mt-5 space-y-3 text-sm">
                                <div class="flex justify-between gap-4">
                                    <span class="text-slate-500">
                                        Adresse IP
                                    </span>
                                    <span class="font-medium text-slate-900">
                                        {{ request()->ip() }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <span class="text-slate-500">
                                        Navigateur
                                    </span>
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