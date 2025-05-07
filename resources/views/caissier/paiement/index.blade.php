@extends('layouts.app')

@section('title', 'Paiement')

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
        
      </div>
      
    </div>
    <div class="container">
    <h1 class="mb-4">Liste des ressortissants</h1>

    <table id="attribution-sold-datatable" class="display table">
        <thead>
            <tr>
                <th>N°</th>
                <th>Raison Sociale</th>
                <th>Forme Juridique</th>
                <th>Secteur d'Activité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    @if ($ressortissants->isNotEmpty())
        @foreach ($ressortissants as $key => $ressortissant)
            <tr>
                <td>#{{ $key + 1 }}</td>
                <td>{{ ucfirst($ressortissant->raisonSociale ?? 'Non défini') }}</td>
                <td>{{ ucfirst($ressortissant->formeJuridique) }}</td>
                <td>{{ ucfirst($ressortissant->secteurActivite) }}</td>
                <td>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal"
                    data-id="{{ $ressortissant->id }}"
                    data-nom="{{ $ressortissant->raisonSociale ?? 'Non défini' }}">Enregistrer paiement</button>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" class="text-center">Aucun ressortissant trouvé.</td>
        </tr>
    @endif
</tbody>
    </table>
</div>
  </div>
</div>

<!-- Modal for Payment Registration -->
<div class="modal custom-modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Enregistrer un paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" action="{{ route('caissier.paiement.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ressortissant_id" id="ressortissant_id">
<div class="mb-3">
    <label class="form-label">Ressortissant</label>
    <input type="text" class="form-control" id="ressortissant_nom" readonly>
</div>
                    <div class="mb-3">
                        <label for="type_cotisation_id" class="form-label">Type de Cotisation</label>
                        <select name="type_cotisation_id" id="type_cotisation_id" class="form-select" >
                            <option value="">-- Sélectionnez un type de cotisation --</option>
                            @if (!empty($typeCotisation) && $typeCotisation->isNotEmpty())
                                @foreach ($typeCotisation as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Aucun type de cotisation disponible</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label>Montant</label>
                        <input type="number" name="montant" class="form-control" required>
                    </div>

                    <div>
                        <label>Mode de paiement</label>
                        <select name="methode_paiement" class="form-select" required>
                        <option value="">-- Sélectionnez le mode de paiement --</option>
                            <option value="Espèces">Espèces</option>
                            <option value="Chèque">Chèque</option>
                            <option value="Virement">Virement</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Valider le paiement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#paymentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var ressortissantId = button.data('id');
        var nomRessortissant = button.data('nom') || 'Non défini';

        var modal = $(this);
        modal.find('#ressortissant_id').val(ressortissantId);
        modal.find('#ressortissant_nom').val(nomRessortissant);
    });

    $('#paymentForm').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        console.log('Données envoyées :', formData);

        $.ajax({
            url: '{{ route("caissier.paiement.store") }}',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#paymentModal').modal('hide');
                    $('#paymentForm')[0].reset();
                    window.location.href = '{{ url("/paiement") }}/' + response.paiement_id + '/recu';
                } else {
                    alert('Une erreur inattendue s\'est produite.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = JSON.parse(xhr.responseText).errors;
                    var errorMessage = 'Erreurs de validation :\n';
                    for (var field in errors) {
                        errorMessage += field + ': ' + errors[field].join(', ') + '\n';
                    }
                    alert(errorMessage);
                } else if (xhr.status === 419) {
                    alert('Erreur de session (CSRF). Veuillez vous déconnecter et vous reconnecter.');
                } else {
                    alert('Une erreur s\'est produite : ' + xhr.status + ' - ' + xhr.statusText);
                    console.log(xhr.responseText);
                }
            }
        });
    });
});
</script>
@endsection