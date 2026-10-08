@php
    $admin = auth()->user();
@endphp

<div x-data="{ tab: 'general', resetModal: false }" class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <div>
                <h1 class="text-xl font-bold text-slate-900">
                    Paramètres administrateur
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Configurez votre application, la sécurité et les préférences système.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('profile') }}"
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                >
                    Profil
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-4">
            {{-- Sidebar Tabs --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                    <button
                        type="button"
                        @click="tab = 'general'"
                        :class="tab === 'general' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100'"
                        class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold"
                    >
                        ⚙️ Général
                    </button>

                    <button
                        type="button"
                        @click="tab = 'security'"
                        :class="tab === 'security' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100'"
                        class="mt-2 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold"
                    >
                        🔐 Sécurité
                    </button>

                    <button
                        type="button"
                        @click="tab = 'mail'"
                        :class="tab === 'mail' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100'"
                        class="mt-2 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold"
                    >
                        ✉️ Email
                    </button>

                    <button
                        type="button"
                        @click="tab = 'system'"
                        :class="tab === 'system' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100'"
                        class="mt-2 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold"
                    >
                        🧰 Système
                    </button>
                </div>
            </aside>

            {{-- Content --}}
            <section class="space-y-8 lg:col-span-3">
                {{-- General --}}
                <div x-show="tab === 'general'" x-cloak class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">
                        Paramètres généraux
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Gérez le nom du site, la description, l’email support et la bannière.
                    </p>

                    @if (session('general_status'))
                        <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('general_status') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="saveGeneral" class="mt-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Nom du site
                            </label>
                            <input
                                type="text"
                                wire:model.blur="site_name"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Description
                            </label>
                            <textarea
                                wire:model.blur="site_description"
                                rows="3"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            ></textarea>
                            @error('site_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Email support
                            </label>
                            <input
                                type="email"
                                wire:model.blur="support_email"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('support_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-5">
                            <label class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        Bannière de maintenance
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Affiche un message d’information dans l’interface.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    wire:model.live="maintenance_banner_enabled"
                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </label>

                            @if ($maintenance_banner_enabled)
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700">
                                        Message
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.blur="maintenance_banner_message"
                                        class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    >
                                </div>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Enregistrer les paramètres généraux
                        </button>
                    </form>
                </div>

                {{-- Security --}}
                <div x-show="tab === 'security'" x-cloak class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">
                        Paramètres de sécurité
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Gérez l’inscription, la vérification email et les exigences admin.
                    </p>

                    @if (session('security_status'))
                        <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('security_status') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="saveSecurity" class="mt-6 space-y-5">
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <label class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        Autoriser les inscriptions
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Active ou désactive la création publique de comptes.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    wire:model.live="registration_enabled"
                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </label>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-5">
                            <label class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        Vérification email obligatoire
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Recommandé avec le middleware Laravel verified.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    wire:model.live="email_verification_required"
                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </label>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-5">
                            <label class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        Forcer la 2FA pour les admins
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Les administrateurs devront configurer la double authentification.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    wire:model.live="admin_force_2fa"
                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Rôle par défaut
                            </label>
                            <select
                                wire:model="default_user_role"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('default_user_role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Enregistrer la sécurité
                        </button>
                    </form>
                </div>

                {{-- Mail --}}
                <div x-show="tab === 'mail'" x-cloak class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">
                        Email / Mailpit
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Testez l’envoi d’emails vers Mailpit ou votre serveur SMTP.
                    </p>

                    @if (session('mail_status'))
                        <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('mail_status') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="sendTestEmail" class="mt-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Email de test
                            </label>
                            <input
                                type="email"
                                wire:model.blur="test_email"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            >
                            @error('test_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Envoyer un email de test
                        </button>
                    </form>

                    <div class="mt-6 rounded-2xl bg-sky-50 p-5 text-sm text-sky-800 ring-1 ring-sky-200">
                        <p class="font-semibold">
                            Mailpit local
                        </p>
                        <p class="mt-2">
                            Interface web :
                        </p>
                        <code class="mt-2 block rounded-xl bg-white px-4 py-3 text-xs">
                            http://localhost:8025
                        </code>
                    </div>
                </div>

                {{-- System --}}
                <div x-show="tab === 'system'" x-cloak class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">
                        Système
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Informations serveur et actions système.
                    </p>

                    @if (session('system_status'))
                        <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('system_status') }}
                        </div>
                    @endif

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-sm text-slate-500">
                                Application
                            </p>
                            <p class="mt-1 font-bold text-slate-900">
                                {{ config('app.name') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-sm text-slate-500">
                                Environnement
                            </p>
                            <p class="mt-1 font-bold text-slate-900">
                                {{ app()->environment() }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-sm text-slate-500">
                                Debug
                            </p>
                            <p class="mt-1 font-bold {{ config('app.debug') ? 'text-amber-600' : 'text-emerald-600' }}">
                                {{ config('app.debug') ? 'Activé' : 'Désactivé' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-sm text-slate-500">
                                Date serveur
                            </p>
                            <p class="mt-1 font-bold text-slate-900">
                                {{ now()->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button
                            type="button"
                            wire:click="clearCache"
                            class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Vider le cache
                        </button>

                        <button
                            type="button"
                            @click="resetModal = true"
                            class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-500"
                        >
                            Restaurer par défaut
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- Reset Modal --}}
    <div
        x-show="resetModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm"
    >
        <div
            @click.outside="resetModal = false"
            class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl"
        >
            <h2 class="text-lg font-bold text-slate-900">
                Restaurer les paramètres ?
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Cette action va remplacer vos paramètres actuels par les valeurs par défaut.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    @click="resetModal = false"
                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Annuler
                </button>

                <button
                    type="button"
                    wire:click="resetDefaults"
                    @click="resetModal = false"
                    class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500"
                >
                    Restaurer
                </button>
            </div>
        </div>
    </div>
</div>
