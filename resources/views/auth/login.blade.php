<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                Se connecter
            </h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="exemple@email.com">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Votre mot de passe">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 focus:ring-blue-500">
                        <span class="ml-2">Se souvenir de moi</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="hover:underline text-blue-600">Mot de passe oublié ?</a>
                    @endif
                </div>

                <!-- Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                        Se connecter
                    </button>
                </div>

            </form>

            <!-- Ou avec les réseaux sociaux -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 mb-3">Ou connectez-vous avec</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('social.redirect', 'google') }}"
                       class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                        Google
                    </a>
                    <a href="{{ route('social.redirect', 'github') }}"
                       class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition duration-300">
                        GitHub
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
