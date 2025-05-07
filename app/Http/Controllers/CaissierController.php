<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Ressortissant;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CaissierController extends Controller
{
    public function index()
    {
        $nombreRessortissants = \App\Models\Ressortissant::count();
        $nombrePaiements = Paiement::whereNotNull('date_paiement')->count();
        // Évolution des paiements (par mois) - Compatible avec PostgreSQL
        $evolutionPaiements = Paiement::select(
            DB::raw("COALESCE(TO_CHAR(date_paiement, 'YYYY-MM'), 'Non daté') as mois"),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy(DB::raw("COALESCE(TO_CHAR(date_paiement, 'YYYY-MM'), 'Non daté')"))
        ->orderBy(DB::raw("COALESCE(TO_CHAR(date_paiement, 'YYYY-MM'), 'Non daté')"))
        ->get();
        
        return view('caissier.dashboard', [
            'nombreRessortissants'=> $nombreRessortissants,
            'nombrePaiements'=>$nombrePaiements,
            'evolutionPaiements' => $evolutionPaiements,
            
        ]);
    }
    public function ressortissants()
    {
        $ressortissants = Ressortissant::all();
        // Récupère uniquement les utilisateurs ayant le rôle "ressortissant" avec leurs informations de ressortissants
        //$ressortissants = DB::table('ressortissants')
        //->join('utilisateurs', 'ressortissants.userId', '=', 'utilisateurs.id')
        //->where('utilisateurs.role', 'ressortissant')
        //->select(
          //  'ressortissants.*', // Toutes les infos du ressortissant
            //'utilisateurs.firstname',
            //'utilisateurs.lastname',
            //'utilisateurs.username',
            //'utilisateurs.phone',
            //'utilisateurs.address',
            //'utilisateurs.role'
        //)
        //->get();

        return view('caissier/ressortissants/index', compact('ressortissants'));
    }

    public function addRessortissant()
    {
        return view('caissier/ressortissants/addRessortissant');

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

        return view('caissier.ressortissants.editRessortissant', compact('utilisateur', 'ressortissant'));
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
    
            return redirect()->route('caissier.ressortissants')->with('success', 'Ressortissant créé avec succès !');
    
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
        return redirect()->route('caissier.ressortissants')->with('success', 'Ressortissant mis à jour avec succès');
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
        return redirect()->route('caissier.ressortissants')->with('success', 'Ressortissant supprimé avec succès');
    }

    public function paiement()
    {
        $ressortissant = Utilisateur::where('role', 'ressortissant')->get(); // ou 'type_utilisateur'
        return view('caissier.paiement.index', compact('ressortissant'));
    }


    public function dashboard()
    {
        // Nombre de ressortissants
        $nombreRessortissants = Ressortissant::count();
        $nombrePaiements = Paiement::whereNotNull('date_paiement')->count();

        // Nombre de paiements effectués
       // $nombrePaiements = Paiement::where('statut', 'effectue')->count();

        // Évolution des paiements (par mois)
        $evolutionPaiements = Paiement::select(
            DB::raw("DATE_FORMAT(date_paiement, '%Y-%m') as mois"),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();
        // Notifications (par exemple, les 5 plus récentes des 7 derniers jours)
        $notifications = Notification::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('caissier.dashboard', [
            'nombreRessortissants'=> $nombreRessortissants,
            'nombrePaiements'=>$nombrePaiements,
            'evolutionPaiements'=>$evolutionPaiements,
            'notifications'=>$notifications
        ]);
    }
   
}
