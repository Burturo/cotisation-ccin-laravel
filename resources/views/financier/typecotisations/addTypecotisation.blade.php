@extends('layouts.app')
@section('title', 'typecotisation')
@section('content')
<form method="POST" action="{{ route('financier.typecotisations.storeType') }}" enctype="multipart/form-data">
    @csrf
    <div class="container card d-flex flex-column justify-content-between" style="height: 83vh;margin-top: 45px !important;">


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

                            <h4 class="title-header mb-0">Informations type cotisation</h4>

                            <div class="d-flex flex-column mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="name" class="form-label">Nom :</label>
                                    <input type="text" id="name" name="name" class="form-control f-input" placeholder="Entrez le nom" required />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="description" class="form-label">Description:</label>
                                    <input type="text" id="description" name="description" class="form-control f-input" placeholder="Entrez la description" required />
                                </div>
                               
    <div class="col-12 col-md-5">
        <label for="montant" class="form-label">Montant:</label>
        <input type="number" id="montant" name="montant" class="form-control f-input" placeholder="Entrez le montant" required />
    </div>
    <div class="col-12 col-md-5">
        <label for="formeJuridique" class="form-label">Forme Juridique</label>
        <select id="formeJuridique" name="formeJuridique" class="form-select" required>
            <option selected disabled>Sélectionner la forme juridique concernée</option>
            <option value="Societés anonymes (S.A)">Societés anonymes (S.A)</option>
            <option value="Societés à responsabilité limitée (S.A.R.L)">Societés à responsabilité limitée (S.A.R.L)</option>
            <option value="Autres societés ou personnes morales">Autres societés ou personnes morales</option>
            <option value="Entreprises individuelles">Entreprises individuelles</option>
        </select>
    </div>
</div>

                           
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('financier.typecotisations') }}" class="btn button-outline" style="margin-right: 20px;">Annuler</a>
                        <button class="btn button" type="submit">
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection