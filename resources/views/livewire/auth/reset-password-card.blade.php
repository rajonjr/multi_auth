<div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200">
    <h1 class="text-2xl font-bold text-slate-900">
        Réinitialiser le mot de passe
    </h1>

    <form method="POST" action="{{ url('/reset-password') }}" class="mt-6 space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input
                name="email"
                type="email"
                value="{{ $email }}"
                required
                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Nouveau mot de passe</label>
            <input
                name="password"
                type="password"
                required
                autocomplete="new-password"
                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Confirmer</label>
            <input
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
        >
            Réinitialiser
        </button>
    </form>
</div>
