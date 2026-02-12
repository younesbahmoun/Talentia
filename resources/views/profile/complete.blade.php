
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body>
    
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                Compléter votre profil
            </h2>
            
            <form method="POST" action="{{ route('profile.save') }}" class="space-y-5">
                @csrf
                
                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                    Rôle
                </label>
                <select name="role" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="candidat">Candidat</option>
                <option value="recruteur">Recruteur</option>
            </select>
        </div>
        
        <!-- Specialite -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Spécialité
            </label>
            <input type="text" name="specialite"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ex: Développeur Web">
        </div>
        
        <!-- Photo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Photo (URL)
            </label>
            <input type="text" name="photo"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Lien de votre photo">
        </div>
        
        <!-- Bio -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Bio
            </label>
            <textarea name="bio" rows="3"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Parlez un peu de vous..."></textarea>
        </div>
        
        <!-- Button -->
        <div>
            <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
            Enregistrer
        </button>
    </div>
</form>

</div>
</div>


</body>
</html>