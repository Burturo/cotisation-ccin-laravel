@extends('layouts.app')

@section('title', 'Cotisation')

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

<h2>Liste des ressortissants à jours</h2>

<form method="GET" action="{{ route('financier.cotisations') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="secteur">Secteur d'activité :</label>
                <select name="secteur" id="secteur" class="form-control custom-input">
                    <option value="">-- Secteur d'activité --</option>
                    @foreach ($secteurs as $secteurOption)
                        <option value="{{ $secteurOption }}" {{ $secteur == $secteurOption ? 'selected' : '' }}>{{ $secteurOption }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="forme">Forme juridique :</label>
                <select name="forme" id="forme" class="form-control custom-input">
                    <option value="">-- Forme juridique --</option>
                    @foreach ($formes as $formeOption)
                        <option value="{{ $formeOption }}" {{ $forme == $formeOption ? 'selected' : '' }}>{{ $formeOption }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a type="button" href="{{ route('financier.cotisations') }}" class="btn btn-add ml-2">Réinitialiser</a>
            </div>
        </div>
        <div class="mt-3">
        <a  type="button" href="{{ route('financier.cotisations.rapportPdf') }}" class="btn btn-primary" target="_blank">Exporter par PDF le rapport des ressortissants à jour</a>
        <a type="button" href="{{ route('financier.export.excel') }}" class="btn btn-primary" target="_blank">Exporter par Excel le rapport des ressortissants à jour</a>

        </div>
    </form>

<table id="attribution-sold-datatable" class="display table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ressortissant</th>
            <th>Rccm</th>
            <th>Secteur d'Activité</th>
            <th>Forme Juridique</th>
            <th>Type Cotisation</th>
            <th>Montant</th>
            <th>Date de paiement</th>
            <th>Référence du Reçu</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($paiements as $key => $paiement)
            <tr>
                <td>#{{ $key + 1 }}</td>
                <td>{{ $paiement->ressortissant ? $paiement->ressortissant->raisonSociale : 'N/A' }}</td>
                <td>{{ $paiement->ressortissant ? $paiement->ressortissant->rccm : 'N/A' }}</td>
                <td>{{ $paiement->ressortissant ? $paiement->ressortissant->secteurActivite : 'N/A' }}</td>
                <td>{{ $paiement->ressortissant ? $paiement->ressortissant->formeJuridique : 'N/A' }}</td>
                <td>{{ $paiement->cotisation && $paiement->cotisation->typeCotisation ? $paiement->cotisation->typeCotisation->name : '—' }}</td>
                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                <td>{{ $paiement->reference }}  <a href="{{ route('paiement.recu', $paiement->id) }}" class="btn btn-sm btn-secondary" target="_blank">Voir le reçu</a></td>
                
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
    </div>
        
</div>

{{ $paiements->links() }}


@endsection
