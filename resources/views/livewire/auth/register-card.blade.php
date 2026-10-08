<div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-slate-900">
            Créer un compte
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Inscription utilisateur.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('/register') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="role" value="user">

        <div>
            <label class="block text-sm font-medium text-slate-700">Nom</label>
            <input
                name="name"
                type="text"
                required
                autocomplete="name"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input
                name="email"
                type="email"
                required
                autocomplete="email"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
            <input
                name="password"
                type="password"
                required
                autocomplete="new-password"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Confirmer le mot de passe</label>
            <input
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
        >
            S’inscrire
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Déjà un compte ?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Connexion
        </a>
    </p>
</div>
