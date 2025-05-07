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
        <h4>Liste des  types de cotisations</h4>
      </div>
      <div>
        <a type="button" class="btn btn-add" href="{{ route('financier.typecotisations.ajouter') }}">
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
                         Nom 
                  </th>
                  <th>
                        Description
                  </th>
                  <th>
                        Montant 
                  </th>
                  <th>
                        Forme Juridique
                  </th>

                  <th>
                      Action
                  </th>
              </tr>
            </thead>
            <tbody>
                @foreach ($type_cotisations  as $key => $type_cotisations)
                    <tr class="fw-medium">
                        <td>#{{ $key + 1 }}</td>
                        <td>{{ ucfirst($type_cotisations->name ) }}</td>
                        <td>{{ ucfirst($type_cotisations->description ) }}</td>
                        <td>{{ ucfirst($type_cotisations->montant ) }}</td>
                        <td>{{ ucfirst($type_cotisations->formeJuridique ) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-icon btn-icon-main voir-details" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetailsTypecotisation"
                                    data-id="{{ $type_cotisations->id }}">
                                <i class="fa fa-eye"></i>
                            </button>
                                <a href="{{ route('financier.typecotisations.edit', $type_cotisations->id) }}" class="btn btn-icon btn-icon-success">
                                    <i class="fa fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="btn btn-icon btn-icon-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#confirmationModal" 
                                    onclick="setDeleteAction('{{ route('financier.typecotisations.destroyType', $type_cotisations->id) }}')">
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
    <div class="modal custom-modal fade" id="modalDetailsTypecotisations" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDetailsTypecotisationsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailsTypecotisationsLabel">Détails du type de la cotisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Image à gauche (4 colonnes) -->

                        
                        <!-- Informations à droite (7 colonnes) -->
                        <div class="col-7 text-start">
                            <!-- Tab navigation -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="type_cotisations-tab" data-bs-toggle="tab" href="#type_cotisations" role="tab" aria-controls="type_cotisations" aria-selected="true">Informations type cotisation</a>
                                </li>
                                
                            </ul>
                            <!-- Tab content -->
                            <div class="tab-content p-4" id="myTabContent">
                                <!-- Onglet Utilisateur -->
                                <div class="tab-pane fade show active" id="type_cotisations" role="tabpanel" aria-labelledby="type_cotisations-tab">
                                    <div class="row">
                                        <!-- Informations à droite (7 colonnes) -->
                                        <div class=" text-start">
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Nom :</strong> <span class="detailName"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Description :</strong> <span class="detailDescription"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Montant :</strong> <span class="detailMontant"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <strong>Forme juridique :</strong> <span class="detailFormeJuridique"></span>
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
                        var typecotisationId = this.getAttribute("data-id");

                        // Requête AJAX pour récupérer les détails de l'utilisateur
                        fetch(`/financier/typecotisations/${typecotisationId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    alert("Typecotisation non trouvé !");
                                    return;
                                }

                                // Informations de l'utilisateur
                                document.querySelectorAll(".detailNom").forEach(el => el.textContent = data.nom);
                                document.querySelectorAll(".detailDescription").forEach(el => el.textContent = data.description);
                                document.querySelectorAll(".detailMontant").forEach(el => el.textContent = data.montant);


                                // Vérification et affichage des informations du ressortissant
                                document.getElementById("detailNom").textContent = data.nom || "Non renseigné";
                                document.getElementById("detailDescription").textContent = data.description || "Non renseigné";
                                document.getElementById("detailMontant").textContent = data.montant || "Non renseigné";
                                
                            .catch(error => {
                                console.error("Erreur lors de la récupération des détails :", error);
                                alert("Une erreur s'est produite lors de la récupération des détails !");
                            });
                    });
                });
            });


        </script>

@endsection
