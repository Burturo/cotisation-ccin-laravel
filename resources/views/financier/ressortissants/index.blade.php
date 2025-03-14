@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<style>
    .custom-card {
        border: 1px solid #ACB6DA !important;
        background-color: #fff !important;
        border-radius: 15px !important;
    }

    .card.custom-card .card-header {
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }

    #attribution-sold-datatable thead tr{
        background: #ebf7ff;
        color: #277fbe;
        border-top: 1px solid #cbd1e6;
        border-bottom: 1px solid #cbd1e6;
    }

    #attribution-sold-datatable.stripe>tbody>tr:nth-child(odd)>*, #attribution-sold-datatable.display>tbody>tr:nth-child(odd)>* {
        box-shadow: none;
        background: #fff;
        color: #045e9e;
        border: none;
        padding: 20px 10px;
    }

    #attribution-sold-datatable.stripe>tbody>tr:nth-child(even)>*, #attribution-sold-datatable.display>tbody>tr:nth-child(even)>* {
        box-shadow: none;
        background: #f0f9ff;
        color: #045e9e;
        border: none;
        padding: 20px 10px;
    }
    .page-item.active .page-link {
      background-color: #045e9e !important;
    }

    #attribution-sold-datatable>thead>tr>th, table.dataTable>thead>tr>td{
        border: none
    }

    #attribution-sold-datatable_wrapper.dt-container .dt-paging .dt-paging-button.disabled{
        color: #9599ae;
    }

    #attribution-sold-datatable_wrapper.dt-container .dt-paging .dt-paging-button.current,#attribution-sold-datatable_wrapper.dt-container .dt-paging .dt-paging-button.current:hover{
        background: #214393;
        color: #fff !important;
    }

    .btn-main{
        border-radius: 10px !important;
        --bs-btn-bg: #ff7946 !important;
        --bs-btn-border-color: #ff7946 !important;
        --bs-btn-color: #ffffff !important;
        padding: 10px 40px !important;
    }

    .btn-main-outline{
        border-radius: 10px !important;
        --bs-btn-bg: #fff5f1 !important;
        --bs-btn-border-color: #ff7946 !important;
        --bs-btn-color: #ff7946 !important;
        padding: 10px 40px !important;
    }

    .search-wrapper{
        position: relative;
        padding: 5px;
        border: 1px solid #c1c6d8;
        border-radius: 10px;
        padding-right: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: flex-start
    }
    .search-wrapper .search-input{
        width: 100%;
        border: none;
        outline: none
    }
    
    .search-icon{    
        position: absolute;
        right: 5px;
        top: 5px;
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ff7946;
        color: #ffffff;
        border-radius: 10px; 
    }

    .custom-input{
        border-radius: 10px !important;
        border: 1px solid #c1c7d7 !important;
        padding: 10px !important;
    }

    .badge-main{
        background: #fff5f1 !important;
        color: #ff7946 !important;
    }

    .btn-icon{
        height: 35px;
        width: 35px;
        border-radius: 10px;
        padding: 0px !important;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .btn-icon-main{
        background: #d4eeff  !important;
        color: #045e9e  !important;
    }

    .btn-icon-secondary{
        background: #ffe3d8  !important;
        color: #ff7946 !important;
    }

    .btn-icon-success{
        background: #c0ffcc   !important;
        color: #268B39 !important;
    }

    .table-container .dt-length{
      display: flex;
    }
    .header-icon{
        height: 40px;
        width: 40px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1aa5f7;
        background: #e6f4ff;
    }
    table.dataTable td.dt-type-numeric{
         text-align: left !important;
    }
</style>
<div class="container-fluid" style="margin-top: 45px;">
  <div class="card p-3 card-table">
    <div class="d-flex justify-content-between mb-2">
      <div class="d-flex">
        <h4>Liste des ressortissants</h4>
      </div>
      <div>
        <a type="button" class="btn btn-add" href="{{ route('financier.ressortissants.ajouter') }}">
          <i class="fa-solid fa-plus"></i> Ajouter
        </a>
      </div>
    </div>
    <div class="table-container px-2">
    <table id="attribution-sold-datatable" class="display table ">
            <thead>
              <tr>
                  <th>
                          Nº
                  </th>
                  <th>
                      Nom & Prénom
                  </th>
                  <th>
                      Nom d'utilisateur
                  </th>
                  <th>
                      Titre1 & Titre2
                  </th>
                  <th>
                    Raison Sociale
                  </th>
                  <th>
                  Forme Juridique
                  </th>
                  <th>
                  Secteur d'Activité
                  </th>

                  <th>
                      Action
                  </th>
              </tr>
            </thead>
            <tbody>
                @foreach ($ressortissants  as $key => $ressortissant)
                    <tr class="fw-medium">
                        <td>#{{ $key + 1 }}</td>
                        <td>{{ $ressortissant->firstname }} {{ $ressortissant->lastname }}</td>
                        <td>{{ ucfirst($ressortissant->username ) }}</td>
                        <td>{{ $ressortissant->titre1  }} {{ $ressortissant->titre2  }}</td>
                        <td>{{ ucfirst($ressortissant->raisonSociale ) }}</td>
                        <td>{{ ucfirst($ressortissant->formeJuridique ) }}</td>
                        <td>{{ ucfirst($ressortissant->secteurActivite ) }}</td>
                        
                        <td>
                            <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-icon btn-icon-main voir-details" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetailsRessortissant"
                                    data-id="{{ $ressortissant->id }}">
                                <i class="fa fa-eye"></i>
                            </button>
                                <a href="{{ route('financier.ressortissants.edit', $ressortissant->id) }}" class="btn btn-icon btn-icon-success">
                                    <i class="fa fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="btn btn-icon btn-icon-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#confirmationModal" 
                                    onclick="setDeleteAction('{{ route('financier.ressortissants.destroy', $ressortissant->id) }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

      </table>
    </div>
  </div>
</div>

    


    <!-- Modification Modal -->
    <div class="modal custom-modal fade" id="modalDetailsRessortissant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDetailsRessortissantLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailsRessortissantLabel">Détails de l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Image à gauche (4 colonnes) -->
                        <div class="col-4 text-center mb-3">
                            <img src="" alt="Photo de l'utilisateur" class="img-thumbnail detailImage" width="150">
                        </div>
                        
                        <!-- Informations à droite (7 colonnes) -->
                        <div class="col-7 text-start">
                            <!-- Tab navigation -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="user-tab" data-bs-toggle="tab" href="#user" role="tab" aria-controls="user" aria-selected="true">Informations Utilisateur</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="ressortissant-tab" data-bs-toggle="tab" href="#ressortissant" role="tab" aria-controls="ressortissant" aria-selected="false">Informations Ressortissant</a>
                                </li>
                            </ul>
                            <!-- Tab content -->
                            <div class="tab-content p-4" id="myTabContent">
                                <!-- Onglet Utilisateur -->
                                <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                                    <div class="row">
                                        <!-- Informations à droite (7 colonnes) -->
                                        <div class=" text-start">
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Nom & Prénom :</strong> <span class="detailNom"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Téléphone :</strong> <span class="detailTelephone"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Genre :</strong> <span class="detailGenre"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Adresse :</strong> <span class="detailAddress"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Nom d'utilisateur :</strong> <span class="detailUsername"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Rôle :</strong> <span class="detailRole"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Onglet Ressortissant -->
                                <div class="tab-pane fade" id="ressortissant" role="tabpanel" aria-labelledby="ressortissant-tab">
                                    <div class="row">
                                        <div class="text-start">
                                            <div class="row mb-3">
                                                <strong class="col-6">Titre 1 :</strong> <span class="col-6" id="detailTitre1"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Titre 2 :</strong> <span class="col-6" id="detailTitre2"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Raison Sociale :</strong> <span class="col-6" id="detailRaisonSociale"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Forme Juridique :</strong> <span class="col-6" id="detailFormeJuridique"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">RCCM :</strong> <span class="col-6" id="detailRccm"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Capital Social :</strong> <span class="col-6" id="detailCapitalSocial"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Cotisation Annuelle :</strong> <span class="col-6" id="detailCotisationAnnuelle"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Secteur d'Activité :</strong> <span class="col-6" id="detailSecteurActivite"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Promoteur :</strong> <span class="col-6" id="detailPromoteur"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Durée de Création :</strong> <span class="col-6" id="detailDureeCreation"></span>
                                            </div>
                                            <div class="row mb-3">
                                                <strong class="col-6">Localité & Région :</strong> <span class="col-6" id="detailLocaliteRegion"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal custom-modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cet ressortissant ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <!-- Formulaire de suppression -->
                    <form id="deleteForm" method="POST" action="" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


        <script>
            // Fonction pour définir l'action du formulaire de suppression
            function setDeleteAction(url) {
                document.getElementById('deleteForm').action = url;
            }
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".voir-details").forEach(button => {
                    button.addEventListener("click", function() {
                        var ressortissantId = this.getAttribute("data-id");

                        // Requête AJAX pour récupérer les détails de l'utilisateur
                        fetch(`/financier/ressortissants/${ressortissantId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    alert("Ressortissant non trouvé !");
                                    return;
                                }

                                // Informations de l'utilisateur
                                document.querySelectorAll(".detailNom").forEach(el => el.textContent = data.firstname + " " + data.lastname);
                                document.querySelectorAll(".detailTelephone").forEach(el => el.textContent = data.phone);
                                document.querySelectorAll(".detailGenre").forEach(el => el.textContent = data.gender);
                                document.querySelectorAll(".detailAddress").forEach(el => el.textContent = data.address);
                                document.querySelectorAll(".detailUsername").forEach(el => el.textContent = data.username);
                                document.querySelectorAll(".detailRole").forEach(el => el.textContent = data.role);
                                document.querySelectorAll(".detailImage").forEach(img => img.src = data.image);

                                // Vérification et affichage des informations du ressortissant
                                document.getElementById("detailTitre1").textContent = data.titre1 || "Non renseigné";
                                document.getElementById("detailTitre2").textContent = data.titre2 || "Non renseigné";
                                document.getElementById("detailRaisonSociale").textContent = data.raisonSociale || "Non renseigné";
                                document.getElementById("detailFormeJuridique").textContent = data.formeJuridique || "Non renseigné";
                                document.getElementById("detailRccm").textContent = data.rccm || "Non renseigné";
                                document.getElementById("detailCapitalSocial").textContent = data.capitalSociale || "Non renseigné";
                                document.getElementById("detailCotisationAnnuelle").textContent = data.cotisationAnnuelle || "Non renseigné";
                                document.getElementById("detailSecteurActivite").textContent = data.secteurActivite || "Non renseigné";
                                document.getElementById("detailPromoteur").textContent = data.promoteur || "Non renseigné";
                                document.getElementById("detailDureeCreation").textContent = data.dureeCreation || "Non renseigné";
                                document.getElementById("detailLocaliteRegion").textContent = data.localiteEtRegion || "Non renseigné";
                            })
                            .catch(error => {
                                console.error("Erreur lors de la récupération des détails :", error);
                                alert("Une erreur s'est produite lors de la récupération des détails !");
                            });
                    });
                });
            });


        </script>

@endsection
