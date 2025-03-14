<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CCIN</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}">
</head>
<body style="background-color: #e5e5e5;">
<div class="container">
        <!-- Début du conteneur principal -->
        <form  method="POST" action="{{ route('password.update') }}" class="conteneur-resetPassword card d-flex flex-row mx-auto">
            @csrf
            <!-- Début du formulaire de connexion -->
            <div class="col-12 p-4 d-flex flex-column">

                <img class="resetPassword-image mx-auto" height="100%" width="100%" src="{{ asset('images/reset_password.png') }}">

                <!-- Affichage des erreurs générales -->
                @if(session('info'))
                    <div class="alert alert-warning">{{ session('info') }}</div>
                @endif

                <div class="my-2">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $username }}" readonly>
                </div>
                <div class="mb-2 mt-2">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-2 mt-2">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <input type="submit" name="submit" class="btn btn-bleu text-white my-4" value="Réinitialiser">
                <!-- Bouton pour soumettre le formulaire -->
            </div>
        </form>
    </div>
</body>
</html>
