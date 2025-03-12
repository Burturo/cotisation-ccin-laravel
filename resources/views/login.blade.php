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
        <form action="{{ route('authentification') }}" method="post" class="conteneur card d-flex flex-row mx-auto">
            @csrf
            <!-- Début du formulaire de connexion -->
            <div class="col-12 col-lg-6 p-4 d-flex flex-column">
                <div class="row text-center">
                    <h2>Connexion</h2>
                    <label>Salut, entrez vos coordonnées pour vous connecter</label>
                </div>

                <!-- Affichage des erreurs générales -->
                @if ($errors->has('login'))
                    <div class="alert alert-danger text-center mt-3">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <div class="mt-4">
                    <label for="InputEmail" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="InputEmail" name="username" placeholder="Nom d'utilisateur" value="{{ old('username') }}" required>
                    <!-- Affichage de l'erreur pour le champ username -->
                </div>

                <div class="mb-2 mt-4">
                    <label for="InputPassword" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Mot de Passe" required>
                    <!-- Affichage de l'erreur pour le champ password -->
                </div>

                <input type="submit" name="submit" class="btn btn-bleu text-white my-4" value="Se connecter">
                <!-- Bouton pour soumettre le formulaire -->
            </div>

            <div class="col d-none d-md-inline-flex">
                <div class="image-connexion">
                    <div class="image1">
                        <div class="d-flex flex-column">
                            <img width="45%" height="50%" class="img-logo" src="/images/logoccin.jpg" alt="">
                            <label class="form-label fs-5 mt-4 text-center px-4" style="color:#4d5961">
                                Chambre de Commerce et d'Industrie du Niger
                            </label>
                            <!-- Image d'illustration pour la connexion -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
