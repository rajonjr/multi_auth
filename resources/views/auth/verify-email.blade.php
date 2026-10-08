@php
    $user = auth()->user();

    $email = $user?->email;
    $name = $user?->name;

    $dashboardRoute = $user && ($user->role ?? null) === 'admin'
        ? route('admin.dashboard')
        : route('dashboard');
@endphp

<x-layouts.auth>
    <div class="w-full max-w-lg">
        <div class="overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-slate-200">
            {{-- Header --}}
            <div class="bg-linear-to-br from-indigo-600 via-indigo-700 to-slate-900 px-8 py-10 text-white">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl ring-1 ring-white/20">
                    ✉️
                </div>

                <h1 class="mt-6 text-center text-2xl font-bold tracking-tight">
                    Vérifiez votre adresse email
                </h1>

                <p class="mt-3 text-center text-sm leading-6 text-indigo-100">
                    Nous avons envoyé un lien de vérification à votre adresse email.
                </p>
            </div>

            {{-- Body --}}
            <div class="px-8 py-8">
                @if (session('status') === 'verification-link-sent')
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                        <div class="flex gap-3">
                            <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                ✓
                            </div>

                            <div>
                                <p class="font-semibold">
                                    Email envoyé avec succès.
                                </p>
                                <p class="mt-1">
                                    Un nouveau lien de vérification a été envoyé à votre adresse email.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('status') === 'email-verified')
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                        Votre email a été vérifié avec succès.
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-lg font-bold text-indigo-700">
                            {{ strtoupper(substr($name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900">
                                {{ $name }}
                            </p>

                            <p class="mt-1 truncate text-sm text-slate-500">
                                {{ $email }}
                            </p>

                            <span class="mt-3 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                Email non vérifié
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-4 text-sm leading-6 text-slate-600">
                    <p>
                        Avant de continuer, veuillez vérifier votre boîte de réception et cliquer sur le lien de vérification.
                    </p>

                    <p>
                        Si vous ne trouvez pas l’email, vérifiez aussi le dossier spam ou courrier indésirable.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="mt-8 space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span>Renvoyer le lien de vérification</span>
                        </button>
                    </form>

                    <a
                        href="{{ $dashboardRoute }}"
                        class="flex w-full items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Retour au dashboard
                    </a>

                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-xl border border-red-300 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-50"
                        >
                            Déconnexion
                        </button>
                    </form>
                </div>

                {{-- Mailpit helper --}}
                @if (app()->environment('local'))
                    <div class="mt-8 rounded-2xl border border-sky-200 bg-sky-50 p-4">
                        <h2 class="text-sm font-semibold text-sky-900">
                            Développement local avec Mailpit
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-sky-700">
                            Ouvrez Mailpit pour voir l’email de vérification capturé localement.
                        </p>

                        <div class="mt-3 rounded-xl bg-white px-4 py-3 font-mono text-xs text-sky-800 ring-1 ring-sky-100">
                            http://localhost:8025
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.auth>