<div class="mx-auto max-w-3xl px-4 py-10">
    <div class="space-y-8">

        <section class="rounded-2xl bg-white p-8 shadow ring-1 ring-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">
                Sécurité du compte
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Gérez la double authentification et vos passkeys.
            </p>
        </section>

        <section class="rounded-2xl bg-white p-8 shadow ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">
                Double authentification
            </h2>

            <div class="mt-6 space-y-4">
                @if (! auth()->user()->two_factor_secret)
                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Activer la 2FA
                        </button>
                    </form>
                @else
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-600">
                            Scannez ce QR Code dans Google Authenticator, Authy, 1Password, etc.
                        </p>

                        <div class="mt-4 rounded-xl bg-white p-4">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>

                    @if (! auth()->user()->two_factor_confirmed_at)
                        <form
                            method="POST"
                            action="{{ url('/user/confirmed-two-factor-authentication') }}"
                            class="space-y-3"
                        >
                            @csrf

                            <input
                                name="code"
                                type="text"
                                placeholder="Code 2FA"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3"
                            >

                            <button
                                type="submit"
                                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                            >
                                Confirmer la 2FA
                            </button>
                        </form>
                    @endif

                    <div class="rounded-xl border border-slate-200 p-4">
                        <h3 class="font-medium text-slate-900">
                            Codes de récupération
                        </h3>

                        <ul class="mt-3 space-y-1 text-sm font-mono text-slate-700">
                            @foreach (auth()->user()->recoveryCodes() as $code)
                                <li>{{ $code }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Régénérer les codes
                        </button>
                    </form>

                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-500"
                        >
                            Désactiver la 2FA
                        </button>
                    </form>
                @endif
            </div>
        </section>

        <section class="rounded-2xl bg-white p-8 shadow ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">
                Passkeys
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Ajoutez une passkey pour vous connecter avec Face ID, Touch ID, Windows Hello ou une clé de sécurité.
            </p>

            <div class="mt-6 flex gap-3">
                <input
                    id="passkey-name"
                    type="text"
                    placeholder="MacBook Pro, iPhone, YubiKey..."
                    class="flex-1 rounded-xl border border-slate-300 px-4 py-3"
                >

                <button
                    type="button"
                    onclick="registerPasskey(document.getElementById('passkey-name').value)"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                >
                    Ajouter
                </button>
            </div>
        </section>
    </div>
</div>
