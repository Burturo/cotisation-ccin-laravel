@extends('layouts.app')

@section('title', 'Modifier paiement')

@section('content')
<form method="POST" action="{{ route('caissier.paiement.update', $ressortissant->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="container card d-flex flex-md-row flex-column justify-content-center p-2 my-2" style="height: 83vh;margin-top: 45px !important;">


        <div class="col-12 col-md-8 p-2 mt-md-5 ms-4 position-relative">
            <div class="col-10 mb-2 border-0 mx-auto">
                <div class="mb-2 border-2">
                    <div class="content-step border-bottom border-2">
                        <div class="row g-2 mx-1">
                
                            <div class="d-flex flex-md-row flex-column justify-content-between">
                                <div class="col-12 col-md-5">
                                    <label for="montant" class="form-label">Montant :</label>
                                    <input type="number" id="montant" name="montant" value="{{ $paiement->montant }}" class="form-control f-input" required />
                                </div>
                                <div class="col-12 col-md-5">
                                <label for="mode_paiement" class="form-label">Mode paiement</label>
                                        <select id="mode_paiement" name="mode_paiement" class="form-select" placeholder="Entrez le mode de paiement" required>
                                            <option selected disabled>Sélectionner le mode de paiement</option>
                                            <option value="Espèces" {{ $paiement->mode_paiement == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                            <option value="Chèque" {{ $paiement->mode_paiement == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                            <option value="Virement" {{ $paiement->mode_paiement == 'Virement' ? 'selected' : '' }}>Virement</option>
                                    </select>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('caissier.paiement') }}" class="btn button-outline" style="margin-right: 20px;">Annuler</a>
                                <button class="btn button" type="submit">Modifier</button>
                            </div>
                </div>
            </div>
        </div>
    </div>
</form>


@endsection
