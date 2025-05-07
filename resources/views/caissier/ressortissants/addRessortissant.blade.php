@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<form method="POST" action="{{ route('caissier.ressortissants.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="container card d-flex flex-md-row flex-column justify-content-center p-2 my-2" style="height: 83vh;margin-top: 45px !important;">
        <div class="col-12 col-md-3">
            <div class="card card-img d-flex mx-auto shadow mt-5 py-4">
                <div class="d-flex flex-column mx-auto">
                <img id="imagePreview" src="/images/profile.png" alt="Aperçu de l'image" class="img-add-employee" style="object-fit: cover; display: block;" />
                    <div class="d-flex justify-content-center mt-3 ms-0">
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
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <h4 class="title-header mb-0">Informations de l'utilisateur</h4>

                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="lastname" class="form-label">Nom :</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control f-input" placeholder="Entrez le nom" required />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="firstname" class="form-label">Prénom :</label>
                                    <input type="text" id="firstname" name="firstname" class="form-control f-input" placeholder="Entrez le prénom" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="gender" class="form-label">Genre :</label>
                                <select id="gender" name="gender" class="form-select" placeholder="Entrez le nom d'utilisateur" required>
                                    <option selected disabled>Sélectionner le genre...</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>


                            <h4 class="title-header mb-0 mt-4">Coordonnées contact</h4>
                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" id="phone" name="phone" class="form-control f-input" placeholder="Entrez le numéro du téléphone" />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" id="address" name="address" class="form-control f-input" placeholder="Entrez l'adresse" required />
                                </div>
                            </div>
                            <h4 class="title-header mb-0 mt-4">Informations du compte</h4>
                            <div class="col-12">
                                <label for="username" class="form-label">Nom d'utilisateur :</label>
                                <input type="text" id="username" name="username" class="form-control f-input" placeholder="Entrez le nom d'utilisateur" required />
                            </div>

                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="password" class="form-label">Mot de passe :</label>
                                    <input type="password" id="password" name="password" class="form-control f-input" placeholder="Entrez le mot de passe" required />
                                </div>

                                <div class="col-12 col-md-5">
                                    <label for="password_confirmation" class="form-label">Mot de passe de confirmation :</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control f-input" placeholder="Confirmer le mot de passe" required />
                                </div>
                            </div>
                                <h4 class="title-header mb-0 mt-4">Informations du ressortissant</h4>
                                <div class="d-flex flex-md-row flex-column justify-content-between">
                                    <div class="col-12 col-md-5">
                                        <label for="titre1" class="form-label">Titre 1</label>
                                        <input type="text" id="titre1" name="titre1" class="form-control f-input" required placeholder="Entrez le titre 1"/>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="titre2" class="form-label">Titre 2</label>
                                        <input type="text" id="titre2" name="titre2" class="form-control f-input" required placeholder="Entrez le titre 2"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-md-row flex-column justify-content-between">
                                    <div class="col-12 col-md-5">
                                        <label for="raisonSociale" class="form-label">Raison Sociale</label>
                                        <input type="text" id="raisonSociale" name="raisonSociale" class="form-control f-input" required placeholder="Entrez la raison sociale"/>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="formeJuridique" class="form-label">Forme Juridique</label>
                                        <select id="formeJuridique" name="formeJuridique" class="form-select" required placeholder="Entrez la forme juridique">
                                            <option selected disabled>Sélectionner la forme juridique...</option>
                                            <option value="Societés anonymes (S.A)">Societés anonymes (S.A)</option>
                                            <option value="Societés à responsabilité limitée (S.A.R.L)">Societés à responsabilité limitée (S.A.R.L)</option>
                                            <option value="Autres societés ou personnes morales">Autres societés ou personnes morales</option>
                                            <option value="Entreprises individuelles">Entreprises individuelles</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="rccm" class="form-label">RCCM</label>
                                    <textarea id="rccm" name="rccm" class="form-control f-input" placeholder="Entrez le rccm" required rows="3"></textarea>
                                </div>
                                <div class="d-flex flex-md-row flex-column justify-content-between">
                                    <div class="col-12 col-md-5">
                                        <label for="promoteur" class="form-label">Promoteur</label>
                                        <input type="text" id="promoteur" name="promoteur" class="form-control f-input" placeholder="Entrez le promoteur"/>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="secteurActivite" class="form-label">Secteur d'activité</label>
                                        <input type="text" id="secteurActivite" name="secteurActivite" class="form-control f-input" required placeholder="Entrez le secteur d'activité"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-md-row flex-column justify-content-between">
                                    <div class="col-12 col-md-5">
                                        <label for="capitalSociale" class="form-label">Capital sociale</label>
                                        <input type="number" id="capitalSociale" name="capitalSociale" class="form-control f-input" placeholder="Entrez le capital sociale" />
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="cotisationAnnuelle" class="form-label">Cotisation annuelle</label>
                                        <input type="number" id="cotisationAnnuelle" name="cotisationAnnuelle" class="form-control f-input" placeholder="Entrez le cotisation annuelle" />
                                    </div>
                                </div>
                                <div class="d-flex flex-md-row flex-column justify-content-between mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="dureeCreation" class="form-label">Durée de création</label>
                                        <input type="text" id="dureeCreation" name="dureeCreation" class="form-control f-input" placeholder="Entrez la durée de création" />
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="localiteEtRegion" class="form-label">Localite et Région</label>
                                        <input type="text" id="localiteEtRegion" name="localiteEtRegion" class="form-control f-input" required placeholder="Entrez la localite et région"/>
                                    </div>
                                    
                                </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('financier.ressortissants') }}" class="btn button-outline" style="margin-right: 20px;">Annuler</a>
                        <button class="btn button" type="submit">
                            Ajouter
                        </button>
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