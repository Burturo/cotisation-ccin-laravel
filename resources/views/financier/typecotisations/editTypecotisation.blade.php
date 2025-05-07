@extends('layouts.app')

@section('title', 'Modifier Typecotisation')

@section('content')
<form method="POST" action="{{ route('financier.typecotisations.updateType', $type_cotisations->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="container card d-flex flex-md-row flex-column justify-content-center p-2 my-2" style="height: 83vh;margin-top: 45px !important;">


        <div class="col-12 col-md-8 p-2 mt-md-5 ms-4 position-relative">
            <div class="col-10 mb-2 border-0 mx-auto">
                <div class="mb-2 border-2">
                    <div class="content-step border-bottom border-2">
                        <div class="row g-2 mx-1">
                
                            <div class="d-flex flex-column mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="name" class="form-label">Nom :</label>
                                    <input type="text" id="name" name="name" value="{{ $type_cotisations->name}}" class="form-control f-input" required />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="description" class="form-label">Description :</label>
                                    <input type="text" id="description" name="description" value="{{ $type_cotisations->description }}" class="form-control f-input" required />
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="montant" class="form-label">Montant :</label>
                                    <input type=" number" id="montant" name="montant" value="{{ $type_cotisations->montant }}" class="form-control f-input" required />
                                </div>
                                <div class="col-12 col-md-5">
                                        <label for="formeJuridique" class="form-label">Forme Juridique</label>
                                        <select id="formeJuridique" name="formeJuridique" class="form-select" placeholder="Entrez la forme juridique" required>

                                            <option selected disabled>Sélectionner la forme juridique concernée</option>
                                            <option value="Societés anonymes (S.A)" {{ $type_cotisations->formeJuridique == 'Societés anonymes (S.A)' ? 'selected' : '' }}>Societés anonymes (S.A)</option>
                                            <option value="Societés à responsabilité limitée (S.A.R.L)" {{ $type_cotisations->formeJuridique == 'Societés à responsabilité limitée (S.A.R.L)' ? 'selected' : '' }}>Societés à responsabilité limitée (S.A.R.L)</option>
                                            <option value="Autres societés ou personnes morales" {{ $type_cotisations->formeJuridique == 'Autres societés ou personnes morales' ? 'selected' : '' }}>Autres societés ou personnes morales</option>
                                            <option value="Entreprises individuelles" {{ $type_cotisations->formeJuridique == 'Entreprises individuelles' ? 'selected' : '' }}>Entreprises individuelles</option>
                                    </select>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('financier.typecotisations') }}" class="btn button-outline" style="margin-right: 20px;">Annuler</a>
                                <button class="btn button" type="submit">Modifier</button>
                            </div>
                </div>
            </div>
        </div>
    </div>
</form>


@endsection
