@php
    $user = auth()->user();

    $dashboardRoute = ($user->role ?? null) === 'admin'
        ? route('admin.dashboard')
        : route('dashboard');

    $emailVerified = ! is_null($user->email_verified_at);
@endphp

<div x-data="{ deleteModal: false }" class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <div>
                <h1 class="text-xl font-bold text-slate-900">
                    Mon profil
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Gérez vos informations personnelles et la sécurité de votre compte.
                </p>
            </div>

            <a
                href="{{ $dashboardRoute }}"
                class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Retour dashboard
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Sidebar Profile --}}
            <aside class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-indigo-600 text-4xl font-bold text-white shadow">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <h2 class="mt-4 text-xl font-bold text-slate-900">
                            {{ $user->name }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $user->email }}
                        </p>

                        <span class="mt-4 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide
                            {{ ($user->role ?? 'user') === 'admin'
                                ? 'bg-purple-100 text-purple-700'
                                : 'bg-indigo-100 text-indigo-700' }}">
                            {{ $user->role ?? 'user' }}
                        </span>
                    </div>

                    <div class="mt-6 space-y-3 border-t border-slate-200 pt-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">ID compte</span>
                            <span class="font-medium text-slate-900">#{{ $user->id }}</span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Email</span>
                            <span class="font-medium {{ $emailVerified ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $emailVerified ? 'Vérifié' : 'Non vérifié' }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Créé le</span>
                            <span class="font-medium text-slate-900">
                                {{ $user->created_at?->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-bold text-slate-900">
                        Raccourcis
                    </h3>

                    <div class="mt-4 space-y-3">
                        <a
                            href="{{ route('security') }}"
                            class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        >
                            <span class="flex w-7 justify-center">
                                <x-ui.icon name="shield" class="h-5 w-5" />
                            </span>
                            Sécurité
                        </a>

                        @if (($user->role ?? null) === 'admin' && Route::has('admin.settings'))
                            <a
                                href="{{ route('admin.settings') }}"
                                class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            >
                                <span class="flex w-7 justify-center">
                                    <x-ui.icon name="cog" class="h-5 w-5" />
                                </span>
                                Paramètres
                            </a>
                        @endif
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <section class="space-y-8 lg:col-span-2">
                {{-- Update Profile --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Informations du profil
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Mettez à jour votre nom et votre adresse email.
                        </p>
                    </div>

                    @if (session('profile_status'))
                        <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('profile_status') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile" class="mt-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Nom
                            </label>
                            <input
                                type="text"
                                wire:model.blur="name"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Email
                            </label>
                            <input
                                type="email"
                                wire:model.blur="email"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (! $emailVerified)
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                <p class="text-sm text-amber-700">
                                    Votre adresse email n’est pas vérifiée.
                                </p>

                                <button
                                    type="button"
                                    wire:click="resendVerificationEmail"
                                    class="mt-3 rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500"
                                >
                                    Renvoyer le lien de vérification
                                </button>
                            </div>
                        @endif

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Enregistrer
                        </button>
                    </form>
                </div>

                {{-- Update Password --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Modifier le mot de passe
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Utilisez un mot de passe long et sécurisé.
                        </p>
                    </div>

                    @if (session('password_status'))
                        <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('password_status') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword" class="mt-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Mot de passe actuel
                            </label>
                            <input
                                type="password"
                                wire:model="current_password"
                                autocomplete="current-password"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Nouveau mot de passe
                            </label>
                            <input
                                type="password"
                                wire:model="password"
                                autocomplete="new-password"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Confirmation
                            </label>
                            <input
                                type="password"
                                wire:model="password_confirmation"
                                autocomplete="new-password"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Changer le mot de passe
                        </button>
                    </form>
                </div>

                {{-- Delete Account --}}
                <div class="rounded-3xl border border-red-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-red-700">
                        Supprimer le compte
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Cette action est irréversible. Toutes vos données liées au compte peuvent être supprimées.
                    </p>

                    <button
                        type="button"
                        @click="deleteModal = true"
                        class="mt-5 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-500"
                    >
                        Supprimer mon compte
                    </button>
                </div>
            </section>
        </div>
    </main>

    {{-- Delete Modal --}}
    <div
        x-show="deleteModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm"
    >
        <div
            @click.outside="deleteModal = false"
            class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl"
        >
            <h2 class="text-lg font-bold text-slate-900">
                Confirmer la suppression
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Entrez votre mot de passe pour supprimer définitivement votre compte.
            </p>

            <form wire:submit.prevent="deleteAccount" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Mot de passe
                    </label>
                    <input
                        type="password"
                        wire:model="delete_password"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >
                    @error('delete_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="deleteModal = false"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500"
                    >
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
