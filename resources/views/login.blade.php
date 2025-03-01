<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CCIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-b from-purple-900 to-black">
    <div class="bg-gradient-to-b from-blue-500 to-orange-500 p-8 rounded-2xl shadow-lg w-80 text-center text-white">
    <img src="{{ asset('images/CCIN.jpeg') }}" alt="CCIN Logo" class="w-20 mx-auto mb-4"> 
        <h2 class="text-2xl font-semibold mb-6">Se Connecter</h2>
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-left">Nom d'utilisateur</label>
                <input type="text" name="nom" class="w-full mt-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-full border-none outline-none focus:ring-2 focus:ring-purple-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-left">Mot de passe</label>
                <input type="password" name="password" class="w-full mt-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-full border-none outline-none focus:ring-2 focus:ring-purple-400" required>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-orange-400 hover:from-blue-600 hover:to-orange-500 text-white font-bold py-2 rounded-full mt-4 font-semibold">Se Connecter</button>
        </form>


       
    </div>
</body>
</html>