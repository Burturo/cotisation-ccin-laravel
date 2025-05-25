@extends('layouts.app')

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

<h2>Liste des Paiements</h2>

<table id="attribution-sold-datatable" class="display table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ressortissant</th>
            <th>Type Cotisation</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Référence</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paiements as $key => $paiement)
            <tr>
                <td>#{{ $key + 1 }}</td>
                <td>{{ $paiement->ressortissant ? $paiement->ressortissant->raisonSociale : 'N/A' }}</td>
                <td>{{ $paiement->cotisation->typeCotisation->name ?? 'N/A' }}</td>
                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                <td>{{ $paiement->reference }}</td>
                <td>
                    <!-- Button to trigger the modal -->
                    <button type="button" class="btn btn-sm btn-info show-payment-details" data-id="{{ $paiement->id }}" data-bs-toggle="modal" data-bs-target="#paymentDetailsModal">
                        Détails
                    </button>
                    <a href="{{ route('paiement.recu', $paiement->id) }}" class="btn btn-sm btn-secondary" target="_blank">Reçu</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
    </div>
        
</div>

{{ $paiements->links() }}

<!-- Modal for Payment Details -->
<div class="modal custom-modal fade" id="paymentDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailsModalLabel">Détail Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
    <dl class="row">
        <dt class="col-sm-4">Ressortissant</dt>
        <dd class="col-sm-8">{{ $paiement->ressortissant->raisonSociale ?? '—'  }}</dd>

        <dt class="col-sm-4">Type de cotisation</dt>
        <dd class="col-sm-8">{{ $paiement->cotisation->typeCotisation->name ?? '—' }}</dd>

        <dt class="col-sm-4">Année</dt>
        <dd class="col-sm-8">{{ $paiement->cotisation->annee ?? '—' }}</dd>

        <dt class="col-sm-4">Montant</dt>
        <dd class="col-sm-8">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</dd>

        <dt class="col-sm-4">Méthode</dt>
        <dd class="col-sm-8">{{ $paiement->methode_paiement }}</dd>

        <dt class="col-sm-4">Date</dt>
        <dd class="col-sm-8">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</dd>

        <dt class="col-sm-4">Référence</dt>
        <dd class="col-sm-8">{{ $paiement->reference }}</dd>
    </dl>
    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close" target="_blank">← Retour à la liste</a>

    <a href="{{ route('paiement.recu', $paiement->id) }}" class="btn btn-primary" target="_blank">
        Voir le reçu
    </a>
</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.show-payment-details').forEach(button => {
                button.addEventListener('click', function () {
                    const paymentId = this.getAttribute('data-id');

                    // Fetch payment details via AJAX
                    fetch(`/paiement/${paymentId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate modal fields
                            document.getElementById('modal-ressortissant').textContent = data.ressortissant?.raison_sociale ?? 'N/A';
                            document.getElementById('modal-type-cotisation').textContent = data.cotisation?.typeCotisation?.nom ?? '—';
                            document.getElementById('modal-annee').textContent = data.cotisation?.annee ?? '—';
                            document.getElementById('modal-montant').textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(data.montant);
                            document.getElementById('modal-methode').textContent = data.methode_paiement;
                            document.getElementById('modal-date').textContent = new Date(data.date_paiement).toLocaleString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                            document.getElementById('modal-reference').textContent = data.reference;

                            // Update the "Voir le reçu" link
                            document.getElementById('modal-recu-link').setAttribute('href', `/paiement/recu/${paymentId}`);
                        })
                        .catch(error => {
                            console.error('Error fetching payment details:', error);
                            alert('Erreur lors du chargement des détails du paiement.');
                        });
                });
            });
        });
    </script>
@endsection