<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {// Récupérer les valeurs des filtres depuis la requête
        $secteur = $request->query('secteur');
        $forme = $request->query('forme');

        // Construire la requête pour les paiements
        $query = Paiement::with(['ressortissant.user', 'typeCotisation'])
            ->where('statut', 'payé');

        // Appliquer les filtres si présents
        if ($secteur) {
            $query->whereHas('ressortissant', function ($q) use ($secteur) {
                $q->where('secteurJctivite', $secteur);
            });
        }

        if ($forme) {
            $query->whereHas('ressortissant', function ($q) use ($forme) {
                $q->where('formeJuridique', $forme);
            });
        }

        // Ajouter la pagination (10 éléments par page)
        $paiements = $query->paginate(10);

        // Récupérer les listes distinctes pour les menus déroulants
        $secteurs = Ressortissant::select('secteurActivite')
            ->distinct()
            ->whereNotNull('secteurActivite')
            ->pluck('secteurActivite');

        $formes = Ressortissant::select('formeJuridique')
            ->distinct()
            ->whereNotNull('formeJuridique')
            ->pluck('formeJuridique');

        return view('financier.cotisations.index', compact('paiements', 'secteurs', 'formes', 'secteur', 'forme'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
