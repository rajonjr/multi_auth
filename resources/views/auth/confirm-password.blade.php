<x-layouts.auth>
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200">
        <h1 class="text-2xl font-bold text-slate-900">
            Confirmer le mot de passe
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Cette action est sensible. Confirmez votre mot de passe.
        </p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/user/confirm-password') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
                <input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                Confirmer
            </button>

            <button
                type="button"
                onclick="confirmWithPasskey()"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Confirmer avec une passkey
            </button>
        </form>
    </div>
</x-layouts.auth>