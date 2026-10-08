<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    @livewireStyles
</head>
<body class="bg-red-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow w-full max-w-md border-t-4 border-red-600">
            <h2 class="text-2xl font-bold mb-6 text-center text-red-600">🔐 Espace Administrateur</h2>
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border rounded px-3 py-2 mt-1 @error('email') border-red-500 @enderror" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Mot de passe</label>
                    <input type="password" name="password"
                           class="w-full border rounded px-3 py-2 mt-1" required>
                </div>
                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="mr-2">
                    <label for="remember" class="text-sm">Se souvenir de moi</label>
                </div>
                <button type="submit"
                        class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</body>
</html>
