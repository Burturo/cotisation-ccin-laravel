@extends('layouts.app')

@section('content')

<h2>Enregistrement de Paiement pour {{ $paiement->ressortissant->raison_sociale }}</h2>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <h1>Détail du paiement #{{ $paiement->id }}</h1>

    <dl class="row">
        <dt class="col-sm-3">Ressortissant</dt>
        <dd class="col-sm-9">{{ $paiement->ressortissant->raison_sociale }}</dd>

        <dt class="col-sm-3">Type de cotisation</dt>
        <dd class="col-sm-9">{{ $paiement->cotisation->typeCotisation->nom ?? '—' }}</dd>

        <dt class="col-sm-3">Année</dt>
        <dd class="col-sm-9">{{ $paiement->cotisation->annee ?? '—' }}</dd>

        <dt class="col-sm-3">Montant</dt>
        <dd class="col-sm-9">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</dd>

        <dt class="col-sm-3">Méthode</dt>
        <dd class="col-sm-9">{{ $paiement->methode_paiement }}</dd>

        <dt class="col-sm-3">Date</dt>
        <dd class="col-sm-9">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</dd>

        <dt class="col-sm-3">Référence</dt>
        <dd class="col-sm-9">{{ $paiement->reference }}</dd>
    </dl>

    <a href="{{ route('paiement.recu', $paiement->id) }}" class="btn btn-primary" target="_blank">
        Voir le reçu
    </a>
    <a href="{{ route('caissier.paiement.indexPaiement') }}" class="btn btn-link">← Retour à la liste</a>
</div>

@endsection
