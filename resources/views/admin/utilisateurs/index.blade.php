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

    .custom-modal .modal-header{
        border-color: #cbd1e6;
    }

    .custom-modal .modal-title{
        color: #214393;
    }

    .custom-modal .modal-content{
        border: none;
        color: #214393;
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
        <h4>Liste des utilisateurs</h4>
      </div>
      <div>
        <a type="button" class="btn btn-add" href="{{ route('admin.utilisateurs.ajouter') }}">
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
                      Téléphone
                  </th>
                  <th>
                      Genre
                  </th>
                  
                  <th>
                      Nom d'utilisateur
                  </th>
                  <th>
                      Rôle
                  </th>
                  <th>
                      Action
                  </th>
              </tr>
            </thead>
            <tbody>
                @foreach ($utilisateurs as $key => $utilisateur)
                    <tr class="fw-medium">
                        <td>#{{ $key + 1 }}</td>
                        <td>{{ $utilisateur->firstname }} {{ $utilisateur->lastname }}</td>
                        <td>{{ ucfirst($utilisateur->phone) }}</td>
                        <td>{{ ucfirst($utilisateur->gender) }}</td>
                        <td>{{ ucfirst($utilisateur->username) }}</td>
                        <td>{{ ucfirst($utilisateur->role) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-icon btn-icon-main voir-details" data-bs-toggle="modal" 
                                        data-bs-target="#modalDetailsUtilisateur"
                                        data-id="{{ $utilisateur->id }}">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.utilisateurs.edit', $utilisateur->id) }}" class="btn btn-icon btn-icon-success">
                                    <i class="fa fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="btn btn-icon btn-icon-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#confirmationModal" 
                                    onclick="setDeleteAction('{{ route('admin.utilisateurs.destroy', $utilisateur->id) }}')">
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
    <div class="modal fade" id="modalDetailsUtilisateur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailsLabel">Détails de l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Image à gauche (4 colonnes) -->
                        <div class="col-4 text-center mb-3">
                            <img id="detailImage" src="" alt="Photo de l'utilisateur" class="img-thumbnail" width="150">
                        </div>
                        
                        <!-- Informations à droite (7 colonnes) -->
                        <div class="col-7 text-start">
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Nom & Prénom :</strong> <span id="detailNom"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Téléphone :</strong> <span id="detailTelephone"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Genre :</strong> <span id="detailGenre"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Adresse :</strong> <span id="detailAddress"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Nom d'utilisateur :</strong> <span id="detailUsername"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Rôle :</strong> <span id="detailRole"></span>
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
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.
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
                        var userId = this.getAttribute("data-id");

                        // Requête AJAX pour récupérer les détails de l'utilisateur
                        fetch(`/admin/utilisateurs/${userId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    alert("Utilisateur non trouvé !");
                                    return;
                                }
                                document.getElementById("detailNom").textContent = data.firstname + " " + data.lastname;
                                document.getElementById("detailTelephone").textContent = data.phone;
                                document.getElementById("detailGenre").textContent = data.gender;
                                document.getElementById("detailAddress").textContent = data.address;
                                document.getElementById("detailUsername").textContent = data.username;
                                document.getElementById("detailRole").textContent = data.role;
                                document.getElementById("detailImage").src = data.image;
                            })
                            .catch(error => console.error("Erreur lors de la récupération des détails :", error));
                    });
                });
            });
        </script>

@endsection
