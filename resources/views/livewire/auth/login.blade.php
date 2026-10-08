<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">🧑 Espace Utilisateur</h2>
        <form wire:submit.prevent="login">
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" wire:model="email"
                       class="w-full border rounded px-3 py-2 mt-1 @error('email') border-red-500 @enderror">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        
            <div class="mb-4">
                <label class="block text-sm font-medium">Mot de passe</label>
                <input type="password" wire:model="password"
                       class="w-full border rounded px-3 py-2 mt-1">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        
            <div class="mb-4 flex items-center">
                <input type="checkbox" wire:model="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm">Se souvenir de moi</label>
            </div>
        
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                <span wire:loading.remove>Se connecter</span>
                <span wire:loading>Connexion en cours...</span>
            </button>
        
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-600">Créer un compte</a>
            </div>
        </form>
    </div>
</div>
