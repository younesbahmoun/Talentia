<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Créer un compte
        </h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Votre nom">
            </div>

            <!-- Prenom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                <input type="text" name="prenom" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Votre prénom">
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                <select name="role" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="candidat">Candidat</option>
                    <option value="recruteur">Recruteur</option>
                </select>
            </div>

            <!-- Specialite -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité</label>
                <input type="text" name="specialite"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Ex: Développeur Web">
            </div>

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo (URL)</label>
                <input type="text" name="photo"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Lien de votre photo">
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Parlez un peu de vous..."></textarea>
            </div>

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

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Confirmez votre mot de passe">
            </div>

            <!-- Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                    S'inscrire
                </button>
            </div>

        </form>

        <!-- Social login -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 mb-3">Ou inscrivez-vous avec</p>
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

        <p class="mt-4 text-center text-sm text-gray-600">
            Déjà inscrit ? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
        </p>

    </div>
</div>

</body>
</html>
