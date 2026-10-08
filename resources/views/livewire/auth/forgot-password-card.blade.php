<div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200">
    <h1 class="text-2xl font-bold text-slate-900">
        Mot de passe oublié
    </h1>

    <p class="mt-2 text-sm text-slate-500">
        Entrez votre email pour recevoir un lien de réinitialisation.
    </p>

    @if (session('status'))
        <div class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/forgot-password') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input
                name="email"
                type="email"
                required
                autocomplete="email"
                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
            >
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
        >
            Envoyer le lien
        </button>
    </form>
</div>
