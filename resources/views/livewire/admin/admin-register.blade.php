<div class="mx-auto max-w-xl px-4 py-10">
    <div class="rounded-2xl bg-white p-8 shadow ring-1 ring-slate-200">
        <h1 class="text-2xl font-bold text-slate-900">
            Créer un administrateur
        </h1>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="create" class="mt-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700">Nom</label>
                <input
                    type="text"
                    wire:model.blur="name"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input
                    type="email"
                    wire:model.blur="email"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
                <input
                    type="password"
                    wire:model.blur="password"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Confirmation</label>
                <input
                    type="password"
                    wire:model.blur="password_confirmation"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3"
                >
            </div>

            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                Créer l’admin
            </button>
        </form>
    </div>
</div>
