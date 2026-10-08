<x-layouts.auth>
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200">
        <h1 class="text-2xl font-bold text-slate-900">
            Vérification 2FA
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Entrez le code de votre application d’authentification ou un code de récupération.
        </p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/two-factor-challenge') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700">Code 2FA</label>
                <input
                    name="code"
                    type="text"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Code de récupération</label>
                <input
                    name="recovery_code"
                    type="text"
                    autocomplete="one-time-code"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                Vérifier
            </button>
        </form>
    </div>
</x-layouts.auth>