<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/SidebarTest.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/fontawesome-free-6.5.2-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.css" rel="stylesheet">
 

</head>
<body style="background-color: #eef8ff;">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <div class="home-section">
            <!-- Navbar -->
            @include('partials.navbar')

            <div class="scroolAsignSubj h-100 px-4 pt-5 haut-rendbody">
                @yield('content')  <!-- Contenu spécifique à chaque page -->
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/SidebarTest.js') }}" defer></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" asp-append-version="true"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.table').DataTable();
            });
        </script>




<!-- Modale pour afficher les informations du profil -->
<div class="modal custom-modal fade" id="profileModal" data-bs-backdrop="static" data-bs-keyboard="false"  tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Profil de l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(Auth::check())
                    <div class="row">
                        <!-- Image à gauche (4 colonnes) -->
                        <div class="col-4 text-center mb-3">
                            <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}" 
                                 alt="Photo de l'utilisateur" class="img-thumbnail detailImage" width="150">
                        </div>
                        <div class="col-7 text-start">
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Nom & Prénom :</strong> 
                                <span class="detailNom">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Téléphone :</strong> 
                                <span class="detailTelephone">{{ Auth::user()->phone ?? 'Non défini' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Genre :</strong> 
                                <span class="detailGenre">{{ Auth::user()->gender ?? 'Non défini' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Adresse :</strong> 
                                <span class="detailAddress">{{ Auth::user()->address ?? 'Non défini' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Nom d'utilisateur :</strong> 
                                <span class="detailUsername">{{ Auth::user()->username ?? Auth::user()->email }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Rôle :</strong> 
                                <span class="detailRole">{{ Auth::user()->role ?? 'Non défini' }}</span>
                            </div>
                        </div>
                   
                    <!-- Champs spécifiques pour les ressortissants -->
                    @if(Auth::user()->role === 'ressortissant' && Auth::user()->ressortissant)
                        <div id="ressortissantFields" class="row">
                            <div class="col-7 text-start">
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Titre 1 :</strong> 
                                    <span class="detailTitre1">{{ Auth::user()->ressortissant->titre1 ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Titre 2 :</strong> 
                                    <span class="detailTitre2">{{ Auth::user()->ressortissant->titre2 ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Raison Sociale :</strong> 
                                    <span class="detailRaisonSociale">{{ Auth::user()->ressortissant->raisonSociale ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Forme Juridique :</strong> 
                                    <span class="detailFormeJuridique">{{ Auth::user()->ressortissant->formeJuridique ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >RCCM :</strong> 
                                    <span class="detailRccm">{{ Auth::user()->ressortissant->rccm ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Capital Social :</strong> 
                                    <span class="detailCapitalSocial">{{ Auth::user()->ressortissant->capitalSociale ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Cotisation Annuelle :</strong> 
                                    <span class=" detailCotisationAnnuelle">{{ Auth::user()->ressortissant->cotisationAnnuelle ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Secteur d'Activité :</strong> 
                                    <span class=" detailSecteurActivite">{{ Auth::user()->ressortissant->secteurActivite ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Promoteur :</strong> 
                                    <span class="detailPromoteur">{{ Auth::user()->ressortissant->promoteur ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Durée de Création :</strong> 
                                    <span class=" detailDureeCreation">{{ Auth::user()->ressortissant->dureeCreation ?? 'Non défini' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong >Localité & Région :</strong> 
                                    <span class="detailLocaliteRegion">{{ Auth::user()->ressortissant->localiteEtRegion ?? 'Non défini' }}</span>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif
                @else
                    <p>Utilisateur non connecté.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
