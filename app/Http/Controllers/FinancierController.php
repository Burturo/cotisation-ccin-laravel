<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Ressortissant;
use App\Models\TypeCotisation;
use App\Models\Cotisation;
use App\Models\Paiement;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Support\Collection;


class FinancierController extends Controller
{
    public function index()
    {
        $nombreRessortissants = \App\Models\Ressortissant::count();
        $nombrePaiements = \App\Models\Paiement::count();
       // Récupère les paiements par mois
    $paiementsParMois = Paiement::selectRaw('EXTRACT(MONTH FROM created_at) as mois, SUM(montant) as total')
    ->groupBy('mois')
    ->orderBy('mois')
    ->pluck('total', 'mois'); // [1 => 10000, 2 => 20000, ...]

// Définir les libellés des mois (associatif)
$moisLabels = collect([
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',

]);

    $notifications = Notification::where('created_at', '>=', now()->subDays(7))
->orderBy('created_at', 'desc')
->take(5)
->get();

// Mapper les montants sur chaque mois (remplir les mois vides avec 0)
$paiements = $moisLabels->map(fn($mois, $num) => $paiementsParMois[$num] ?? 0);

return view('financier.dashboard', [
    'moisLabels' => $moisLabels->values(), // juste les noms
    'paiements' => $paiements->values(), // les montants correspondants
    'nombreRessortissants' => $nombreRessortissants,
    'nombrePaiements' => $nombrePaiements,
     'notifications'=>$notifications
]);
    }

    public function ressortissants()
    {
        // Récupère uniquement les utilisateurs ayant le rôle "ressortissant" avec leurs informations de ressortissants
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

        return view('financier/ressortissants/index', compact('ressortissants'));
    }

    public function addRessortissant()
    {
        return view('financier/ressortissants/addRessortissant');

    }

    public function getRessortissant($id)
    {
        // Trouver le ressortissant ou retourner une erreur 404
        $ressortissant = Ressortissant::findOrFail($id);

        // Récupérer l'utilisateur associé (s'il existe)
        $utilisateur = $ressortissant->utilisateur;

        // Fusionner les données du ressortissant et de l'utilisateur
        $data = [
            'id' => $ressortissant->id,
            'titre1' => $ressortissant->titre1,
            'titre2' => $ressortissant->titre2,
            'raisonSociale' => $ressortissant->raisonSociale,
            'formeJuridique' => $ressortissant->formeJuridique,
            'rccm' => $ressortissant->rccm,
            'capitalSociale' => $ressortissant->capitalSociale,
            'cotisationAnnuelle' => $ressortissant->cotisationAnnuelle,
            'secteurActivite' => $ressortissant->secteurActivite,
            'promoteur' => $ressortissant->promoteur,
            'dureeCreation' => $ressortissant->dureeCreation,
            'localiteEtRegion' => $ressortissant->localiteEtRegion,

            // Infos de l'utilisateur associé
            'lastname' => $utilisateur->lastname,
            'firstname' => $utilisateur->firstname,
            'gender' => $utilisateur->gender,
            'phone' => $utilisateur->phone,
            'address' => $utilisateur->address,
            'username' => $utilisateur->username,
            'role' => $utilisateur->role,
            'image' => $utilisateur->image ? asset('storage/' . $utilisateur->image) : asset('/images/profile.png'),
        ];

        return response()->json($data);
    }


    public function editRessortissant($id)
    {
        // Trouver le ressortissant avec l'ID fourni
        $ressortissant = Ressortissant::findOrFail($id);

        // Trouver l'utilisateur associé au ressortissant
        $utilisateur = $ressortissant->utilisateur; // On suppose que la relation est définie correctement

        return view('financier.ressortissants.editRessortissant', compact('utilisateur', 'ressortissant'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:utilisateurs,username',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'titre1' => 'required|string|max:255',
            'titre2' => 'required|string|max:255',
            'raisonSociale' => 'required|string',
            'formeJuridique' => 'required|string|in:Societés anonymes (S.A),Societés à responsabilité limitée (S.A.R.L),Autres societés ou personnes morales,Entreprises individuelles',
            'rccm' => 'required|string',
            'promoteur' => 'nullable|string',
            'secteurActivite' => 'required|string',
            'capitalSociale' => 'nullable|numeric|min:0',
            'cotisationAnnuelle' => 'nullable|numeric|min:0',
            'dureeCreation' => 'nullable|string|max:255',
            'localiteEtRegion' => 'required|string|max:255',
        ], [
            'username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'titre1.required' => 'Le champ Titre 1 est obligatoire.',
            'titre2.required' => 'Le champ Titre 2 est obligatoire.',
            'raisonSociale.required' => 'Le champ Raison Sociale est obligatoire.',
            'formeJuridique.required' => 'Le champ Forme Juridique est obligatoire.',
            'formeJuridique.in' => 'La Forme Juridique sélectionnée est invalide.',
            'rccm.required' => 'Le champ RCCM est obligatoire.',
            'secteurActivite.required' => "Le champ Secteur d'activité est obligatoire.",
            'capitalSociale.numeric' => 'Le Capital social doit être un nombre.',
            'cotisationAnnuelle.numeric' => 'La Cotisation annuelle doit être un nombre.',
            'localiteEtRegion.required' => 'Le champ Localité et Région est obligatoire.',
        ]);
    
        try {
            // Gestion de l'image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
            }
    
            // Création de l'utilisateur
            $utilisateur = Utilisateur::create([
                'lastname' => $request->lastname,
                'firstname' => $request->firstname,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'username' => $request->username,
                'role' => 'ressortissant',
                'password' => Hash::make($request->password),
                'image' => $imagePath,
            ]);
    
            // Création du ressortissant
            Ressortissant::create([
                'titre1' => $request->titre1,
                'titre2' => $request->titre2,
                'raisonSociale' => $request->raisonSociale,
                'formeJuridique' => $request->formeJuridique,
                'rccm' => $request->rccm,
                'capitalSociale' => $request->capitalSociale,
                'cotisationAnnuelle' => $request->cotisationAnnuelle,
                'secteurActivite' => $request->secteurActivite,
                'promoteur' => $request->promoteur,
                'dureeCreation' => $request->dureeCreation,
                'localiteEtRegion' => $request->localiteEtRegion,
                'userId' => $utilisateur->id
            ]);
    
            return redirect()->route('financier.ressortissants')->with('success', 'Ressortissant créé avec succès !');
    
        } catch (\Illuminate\Database\QueryException $e) {
            // Gestion d'erreur spécifique pour les erreurs de base de données
            return redirect()->back()->with('error', 'Erreur de base de données : ' . $e->getMessage());
        } catch (\Exception $e) {
            // Gestion générale pour toutes les autres erreurs
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du ressortissant. Détails de l\'erreur : ' . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
        // Trouver le ressortissant avec son id, ou retourner une erreur 404 si non trouvé
        $ressortissant = Ressortissant::findOrFail($id);

        // Trouver l'utilisateur associé au ressortissant
        $utilisateur = $ressortissant->utilisateur; // Assure-toi que la relation est bien définie dans le modèle

        // Validation des données
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:utilisateurs,username,' . $utilisateur->id,
            'password' => 'nullable|string|min:6|confirmed', // Nullable pour ne pas obliger la modification
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'titre1' => 'required|string|max:255',
            'titre2' => 'required|string|max:255',
            'raisonSociale' => 'required|string',
            'formeJuridique' => 'required|string|in:Societés anonymes (S.A),Societés à responsabilité limitée (S.A.R.L),Autres societés ou personnes morales,Entreprises individuelles',
            'rccm' => 'required|string',
            'promoteur' => 'nullable|string',
            'secteurActivite' => 'required|string',
            'capitalSociale' => 'nullable|numeric|min:0',
            'cotisationAnnuelle' => 'nullable|numeric|min:0',
            'dureeCreation' => 'nullable|string|max:255',
            'localiteEtRegion' => 'required|string|max:255',
        ], [
            'username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'titre1.required' => 'Le champ Titre 1 est obligatoire.',
            'titre2.required' => 'Le champ Titre 2 est obligatoire.',
            'raisonSociale.required' => 'Le champ Raison Sociale est obligatoire.',
            'formeJuridique.required' => 'Le champ Forme Juridique est obligatoire.',
            'formeJuridique.in' => 'La Forme Juridique sélectionnée est invalide.',
            'rccm.required' => 'Le champ RCCM est obligatoire.',
            'secteurActivite.required' => "Le champ Secteur d'activité est obligatoire.",
            'capitalSociale.numeric' => 'Le Capital social doit être un nombre.',
            'cotisationAnnuelle.numeric' => 'La Cotisation annuelle doit être un nombre.',
            'localiteEtRegion.required' => 'Le champ Localité et Région est obligatoire.',
        ]);

        // Mise à jour des informations de l'utilisateur
        if ($utilisateur) {
            $utilisateur->update([
                'lastname' => $request->lastname,
                'firstname' => $request->firstname,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'username' => $request->username,
            ]);

            // Mise à jour du mot de passe si fourni
            if ($request->filled('password')) {
                $utilisateur->password = Hash::make($request->password);
            }

            // Mise à jour de l'image si une nouvelle est envoyée
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
                $utilisateur->image = $imagePath;
            }

            $utilisateur->save();
        }

        // Mise à jour des informations du ressortissant
        $ressortissant->update([
            'titre1' => $request->titre1,
            'titre2' => $request->titre2,
            'raisonSociale' => $request->raisonSociale,
            'formeJuridique' => $request->formeJuridique,
            'rccm' => $request->rccm,
            'capitalSociale' => $request->capitalSociale,
            'cotisationAnnuelle' => $request->cotisationAnnuelle,
            'secteurActivite' => $request->secteurActivite,
            'promoteur' => $request->promoteur,
            'dureeCreation' => $request->dureeCreation,
            'localiteEtRegion' => $request->localiteEtRegion,
        ]);

        // Retourner un message de succès
        return redirect()->route('financier.ressortissants')->with('success', 'Ressortissant mis à jour avec succès');
    }


    public function destroy($id) 
    {
        // Trouver le ressortissant avec l'ID fourni
        $ressortissant = Ressortissant::findOrFail($id);

        // Trouver l'utilisateur associé au ressortissant
        $utilisateur = $ressortissant->utilisateur; // On suppose que la relation est définie correctement

        // Supprimer l'utilisateur
        if ($utilisateur) {
            $utilisateur->delete();
        }

        // Suppression du ressortissant
        $ressortissant->delete();

        // Retourner un message de succès
        return redirect()->route('financier.ressortissants')->with('success', 'Ressortissant supprimé avec succès');
    }

    

    //pour type cotisation

    public function typecotisations()
    {
        // Récupère recupére les types de cotisations
        $type_cotisations = DB::table('type_cotisations')
        ->select(
            'type_cotisations.*', // Toutes les infos du ressortissant
        )
        ->get();

        return view('financier/typecotisations/index', compact('type_cotisations'));
    }

    public function addTypecotisation()
    {
        return view('financier/typecotisations/addTypecotisation');

    }

    public function getTypecotisation($id)
    {
        // Trouver le type  ou retourner une erreur 404
        $type_cotisations = TypeCotisation::findOrFail($id);

        // Fusionner les données du ressortissant et de l'utilisateur
        $data = [
            'id' => $type_cotisations->id,
            'name' => $type_cotisations->name,
            'description' => $type_cotisations->description,
            'montant' => $type_cotisations->montant,
            'formeJuridique' => $type_cotisations->formeJuridique,
            
        ];

        return response()->json($data);
    }


    public function editTypecotisation($id)
    {
        // Trouver le type avec l'ID fourni
        $type_cotisations = TypeCotisation::findOrFail($id);

        return view('financier.typecotisations.editTypecotisation', compact('type_cotisations'));
    }

    public function storeType(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'montant' => 'nullable|string|max:255',
            'formeJuridique' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'montant.required' => 'Le champ montant est obligatoire.',
            
        ]);
    
        try {
    
            // Création du type
            $type_cotisations = TypeCotisation::create([
                'name' => $request->name,
                'description' => $request->description,
                'montant' => $request->montant,
                'formeJuridique' => $request->formeJuridique,
                
            ]);
    
            return redirect()->route('financier.typecotisations')->with('success', 'Type de cotisation créé avec succès !');
    
        } catch (\Illuminate\Database\QueryException $e) {
            // Gestion d'erreur spécifique pour les erreurs de base de données
            return redirect()->back()->with('error', 'Erreur de base de données : ' . $e->getMessage());
        } catch (\Exception $e) {
            // Gestion générale pour toutes les autres erreurs
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du type de cotisation. Détails de l\'erreur : ' . $e->getMessage());
        }
    }
    
    public function updateType(Request $request, $id)
    {
        // Trouver le type avec son id, ou retourner une erreur 404 si non trouvé
        $type_cotisations = TypeCotisation::findOrFail($id);

        // Validation des données
        $request->validate([
           'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'montant' => 'nullable|string|max:255',
            'formeJuridique' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'montant.required' => 'Le champ montant est obligatoire.',
        ]);

       
        // Mise à jour des informations
        $type_cotisations->update([
            'name' => $request->name,
                'description' => $request->description,
                'montant' => $request->montant,
                'formeJuridique' => $request->formeJuridique,
        ]);

        // Retourner un message de succès
        return redirect()->route('financier.typecotisations')->with('success', 'Type de cotisation mis à jour avec succès');
    }


    public function destroyType($id) 
    {
        // Trouver le type avec l'ID fourni
        $type_cotisations = TypeCotisation::findOrFail($id);

        // Suppression du type
        $type_cotisations->delete();

        // Retourner un message de succès
        return redirect()->route('financier.typecotisations')->with('success', 'Type cotisation supprimé avec succès');
    }

    //public function cotisations()
    //{
      //  $cotisations = Cotisation::with('typeCotisation')->paginate(10); // Fetch cotisations with type
        //return view('financier.cotisations.index', compact('cotisations'));
    //}

    public function cotisations(Request $request)
{
    // Récupérer les valeurs des filtres depuis la requête
    $secteur = $request->query('secteur');
    $forme = $request->query('forme');

    // Construire la requête pour les paiements
    $query = Paiement::with(['ressortissant.utilisateur', 'typeCotisation']);


    // Appliquer les filtres si présents
    if ($secteur) {
        $query->whereHas('ressortissant', function ($q) use ($secteur) {
            $q->where('secteurActivite', $secteur);
        });
    }

    if ($forme) {
        $query->whereHas('ressortissant', function ($q) use ($forme) {
            $q->where('formeJuridique', $forme);
        });
    }

    // Ajouter la pagination (10 éléments par page)
    $paiements = $query->orderBy('id', 'asc')->paginate(10);

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

    public function downloadPaymentsReport()
    {
        $ressortissants = Ressortissant::whereHas('paiements')
            ->with(['paiements'])
            ->orderBy('raisonSociale')
            ->get();

        $pdf = Pdf::loadView('financier.cotisations.rapportPdf', compact('ressortissants'));
        return $pdf->download('rapport_paiements_' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel()
{
    $ressortissants = \App\Models\Ressortissant::whereHas('paiements')
        ->with('paiements')
        ->orderBy('raisonSociale')
        ->get()
        ->map(function ($ressortissant) {
            return [
                'Raison sociale' => $ressortissant->raisonSociale,
                'RCCM' => $ressortissant->rccm,
                'Forme juridique' => $ressortissant->formeJuridique,
                'Secteur d activité ' => $ressortissant->secteurActivite,
                'Nombre de paiements' => $ressortissant->paiements->count(),
                'Total payé' => $ressortissant->paiements->sum('montant'),
                'Dernier paiement' => optional($ressortissant->paiements->last())->created_at,
            ];
        });

    $export = new class($ressortissants) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
        private $data;

        public function __construct($data)
        {
            $this->data = $data;
        }

        public function collection()
        {
            return collect($this->data);
        }

        public function headings(): array
        {
            return [
                'Raison sociale',
                'RCCM',
                'Forme juridique',
                'Secteur Activité',
                'Nombre de paiements',
                'Total payé',
                'Dernier paiement',
            ];
        }
    };

    return Excel::download($export, 'rapport_paiements_' . now()->format('Ymd') . '.xlsx', ExcelFormat::XLSX);
}

public function dashboard()
{
    // Exemple : Récupérer les paiements par mois pour l'évolution des cotisations
    $paiementsParMois = Paiement::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(montant) as total')
        ->groupByRaw('YEAR(created_at), MONTH(created_at)')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

    // Passer les données à la vue
    return view('dashboard', compact('paiementsParMois'));
}

}
