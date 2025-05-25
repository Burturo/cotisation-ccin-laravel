<?php

namespace App\Http\Controllers;

use App\Models\Ressortissant;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TypeCotisation;
use App\Models\Cotisation;

class PaiementController extends Controller
{
    public function ressortissants($id)
{// Récupère uniquement les utilisateurs ayant le rôle "ressortissant" avec leurs informations de ressortissants
    $ressortissants = DB::table('ressortissants')
    ->join('utilisateurs', 'ressortissants.userId', '=', 'utilisateurs.id')
    ->where('utilisateurs.role', 'ressortissant')
    ->select(
        'ressortissants.*', // Toutes les infos du ressortissant
        'utilisateurs.firstname',
        'utilisateurs.lastname',
        'utilisateurs.username',
        'utilisateurs.phone',
        'utilisateurs.address',
        'utilisateurs.role'
    )
    ->get();

    return view('caissier.paiement.index', compact( 'ressortissants'));
}
    public function index()
    {//$user = auth()->user();

       // if ($user->hasRole('Financier')) {
            // On récupère uniquement les ressortissants qui ont au moins un paiement
         //   $ressortissants = Ressortissant::whereHas('paiement')
           //                               ->with('paiement')
             //                             ->get();
    
            // Passe bien $ressortissants à la vue
            //return view('financier.paiement.index', compact('ressortissants'));
    
        //} else {
            // Tous les ressortissants pour le caissier
            $ressortissants = Ressortissant::all();
            $typeCotisation = TypeCotisation::all(); // Récupérer tous les types de cotisation
            $cotisations = Cotisation::where('annee', 2025)->get();

        return view('caissier.paiement.index', compact('ressortissants', 'typeCotisation', 'cotisations'));
    
           
    }
    public function create($id)
{
    $ressortissants = Ressortissant::findOrFail($id);
    // Récupère le type “Annuelle”
    $typeAnnuel = TypeCotisation::where('name', 'Annuelle')->firstOrFail();

    // Récupère les cotisations de ce type (avec leur année)
    $cotisations = Cotisation::where('type_cotisation_id', $typeAnnuel->id)
                              ->get();
    return view('caissier.paiement.create', compact('ressortissants','cotisations'));
}



public function store(Request $request)
{
    try {
        \Log::info('Données reçues :', $request->all());

        // Validation des données
        $validated = $request->validate([
            'ressortissant_id' => 'required|exists:ressortissants,id',
            'type_cotisation_id' => 'required|exists:type_cotisations,id',
            'montant' => 'required|numeric',
            'methode_paiement' => 'required|string|max:255',
        ]);

        \Log::info('Validation passée');

        // Option 1 : Créer une cotisation si nécessaire
        $cotisationData = [
            'type_cotisation_id' => $request->type_cotisation_id,
            'annee' => 2025,
            'ressortissant_id' => $request->ressortissant_id, 
            'date_cotisation' => now(),
        ];
        \Log::info('Données pour Cotisation::firstOrCreate :', $cotisationData);

        $cotisations = Cotisation::firstOrCreate(
            $cotisationData,
            ['montant' => 0] // Valeur par défaut pour 'montant'
        );
        \Log::info('Cotisation créée ou trouvée :', $cotisations->toArray());

        // Récupération du ressortissant
        $ressortissant = Ressortissant::findOrFail($request->ressortissant_id);

        \Log::info('Ressortissant trouvé :', $ressortissant->toArray());

        // Création de la référence
        $ref = 'REC-' . now()->format('Ymd') . '-' . rand(100, 999);
        \Log::info('Référence générée :', ['ref' => $ref]);

        // Préparer les données du paiement
        $paiementData = [
            'ressortissant_id' => $ressortissant->id,
            'cotisation_id' => $cotisations->id, 
            'montant' => $request->montant,
            'methode_paiement' => $request->methode_paiement,
            'date_paiement' => now(),
            'reference' => $ref,
        ];
        \Log::info('Données pour Paiement::create :', $paiementData);

        // Enregistrement du paiement
        $paiement = Paiement::create($paiementData);
        \Log::info('Paiement créé :', $paiement->toArray());

        // Réponse JSON pour AJAX
        return response()->json([
            'success' => true,
            'message' => 'Paiement enregistré avec succès.',
            'paiement_id' => $paiement->id,
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Erreur dans store : ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur : ' . $e->getMessage(),
        ], 500);
    }

    
    // Redirection avec succès
    return redirect()->route('paiement.recu', $paiement->id)
               ->with('success', 'Paiement enregistré avec succès.');
    
}
    public function indexPaiement()
    {
        // Charge les paiements avec le ressortissant pour la vue
       // $paiements = Paiement::with('ressortissant')
         //                     ->latest('date_paiement')
           //                   ->paginate(20);
           $paiements = Paiement::with(['ressortissant', 'cotisation.typeCotisation'])
           ->orderBy('id', 'asc')
           ->paginate(10);
        return view('caissier.paiement.indexPaiement', compact('paiements'));
    }

    public function show(Paiement $paiement)
{
    $paiement->load('ressortissant', 'cotisation.typeCotisation');
    $paiement = Paiement::with('ressortissant', 'cotisation.typeCotisation')
    ->whereNotNull('cotisation_id')
    ->get();


    if (request()->expectsJson()) {
        return response()->json($paiement);
    }

    return view('caissier.paiement.show', compact('paiement'));
}

    public function recuPDF($id)
    {
        $paiement = Paiement::with('ressortissant')->findOrFail($id);
        $pdf = Pdf::loadView('caissier.paiement.recu', compact('paiement'));
        return $pdf->stream('recu_'.$paiement->reference.'.pdf');
    }

    public function edit(Paiement $paiement)
    {
        return view('paiement.editPaiement', compact('paiement'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'mode_paiement' => 'required|string|max:255',
        ]);

        $paiement->update([
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
        ]);

        return redirect()->route('paiement.index')->with('success', 'Paiement modifié avec succès.');
    }
}