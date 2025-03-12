@extends('layouts.app')

@section('title', 'Modifier Utilisateur')

@section('content')
<form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="container card d-flex flex-md-row flex-column justify-content-center p-2 my-2" style="height: 83vh;margin-top: 45px !important;">
        <div class="col-12 col-md-3">
            <div class="card card-img d-flex mx-auto shadow mt-5 py-4">
                <div class="d-flex flex-column mx-auto">
                <img id="imagePreview" src="{{ $utilisateur->image ? asset('storage/' . $utilisateur->image) : '/images/profile.png' }}" class="img-add-employee" style="object-fit: cover; display: block;" />
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn-upload-img" type="button" onclick="document.getElementById('fileInput').click()">
                            <i class="fa-solid fa-arrow-up-from-bracket me-2"></i>
                            Upload
                        </button>
                        <input type="file" id="fileInput" name="image" style="display: none" accept="image/*" onchange="previewImage(event)" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 p-2 mt-md-5 ms-4 position-relative">
            <div class="col-10 mb-2 border-0 mx-auto">
                <div class="mb-2 border-2">
                    <div class="content-step border-bottom border-2">
                        <div class="row g-2 mx-1">
                            <h4 class="title-header mb-0">Informations de l'utilisateur</h4>

                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="lastname" class="form-label">Nom :</label>
                                    <input type="text" id="lastname" name="lastname" value="{{ $utilisateur->lastname }}" class="form-control f-input" required />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="firstname" class="form-label">Prénom :</label>
                                    <input type="text" id="firstname" name="firstname" value="{{ $utilisateur->firstname }}" class="form-control f-input" required />
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="gender" class="form-label">Genre :</label>
                                <select id="gender" name="gender" class="form-select" required>
                                    <option value="M" {{ $utilisateur->gender == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ $utilisateur->gender == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>

                            <h4 class="title-header mb-0 mt-4">Coordonnées contact</h4>
                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" id="phone" name="phone" value="{{ $utilisateur->phone }}" class="form-control f-input" />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" id="address" name="address" value="{{ $utilisateur->address }}" class="form-control f-input" required />
                                </div>
                            </div>

                            <h4 class="title-header mb-0 mt-4">Informations du compte</h4>
                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="username" class="form-label">Nom d'utilisateur :</label>
                                    <input type="text" id="username" name="username" value="{{ $utilisateur->username }}" class="form-control f-input" readonly />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="role" class="form-label">Rôle :</label>
                                    <select id="role" name="role" class="form-select" required>
                                        <option value="ressortissant" {{ $utilisateur->role == 'ressortissant' ? 'selected' : '' }}>Ressortissant</option>
                                        <option value="caissier" {{ $utilisateur->role == 'caissier' ? 'selected' : '' }}>Caissier</option>
                                        <option value="financier" {{ $utilisateur->role == 'financier' ? 'selected' : '' }}>Financier</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="password" class="form-label">Mot de passe :</label>
                                    <input type="password" id="password" name="password" class="form-control f-input" placeholder="Entrez le mot de passe" />
                                </div>

                                <div class="col-12 col-md-5">
                                    <label for="password_confirmation" class="form-label">Mot de passe de confirmation :</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control f-input" placeholder="Confirmer le mot de passe" />
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('admin.utilisateurs') }}" class="btn button-outline" style="margin-right: 20px;">Annuler</a>
                                <button class="btn button" type="submit">Modifier</button>
                            </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function previewImage(event) {
        const input = event.target;
        const img = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function() {
                img.src = reader.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // Remettre l'image par défaut si aucun fichier n'est sélectionné
            img.src = "/images/profile.png"; // Assure-toi que l'image par défaut existe
        }
    }
</script>

@endsection
