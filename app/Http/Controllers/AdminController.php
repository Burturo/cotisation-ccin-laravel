<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Ressortissant;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin/dashboard');

    }
    public function utilisateurs()
    {
        $utilisateurs = Utilisateur::all(); // Récupère tous les utilisateurs
        return view('admin/utilisateurs/index', compact('utilisateurs'));

    }
    public function addUser()
    {
        return view('admin/utilisateurs/addUser');

    }

    public function getUtilisateur($id)
    {
        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Récupérer l'image (en s'assurant qu'elle existe dans le stockage)
        $utilisateurData = $utilisateur->toArray();
        $utilisateurData['image'] = $utilisateur->image 
            ? asset('storage/' . $utilisateur->image) 
            : asset('/images/profile.png'); // Image par défaut si aucune image n'est trouvée

        // Récupérer les informations du ressortissant si l'utilisateur a le rôle "ressortissant"
        $ressortissant = null;
        if ($utilisateur->role === 'ressortissant') {
            $ressortissant = Ressortissant::where('userId', $utilisateur->id)->first();
        }

        // Ajouter les champs du ressortissant s'il existe
        if ($ressortissant) {
            $utilisateurData['ressortissant'] = [
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
            ];
        }

        // Retourner l'objet utilisateur avec les informations complètes
        return response()->json($utilisateurData);
    }



    public function editUser($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);


        // Récupérer les informations du ressortissant si l'utilisateur a le rôle "ressortissant"
        $ressortissant = null;
        if ($utilisateur->role === 'ressortissant') {
            $ressortissant = Ressortissant::where('userId', $utilisateur->id)->first();
        }

        return view('admin.utilisateurs.editUser', compact('utilisateur', 'ressortissant'));
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
            'role' => 'required|in:ressortissant,caissier,financier',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
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
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'image' => $imagePath,
            ]);

            if ($request->role === 'ressortissant') {
                $request->validate([
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
                    'userId' => $utilisateur->id, 
                ]);
            }

            return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur créé avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.');
        }

    }

    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        // Validation des données
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:utilisateurs,username,'.$id,
            'role' => 'required|in:ressortissant,caissier,financier',
            'password' => 'nullable|string|min:6|confirmed', // Nullable pour ne pas obliger la modification
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
        ], [
            'username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            
        ]);

        // Mise à jour des informations
        $utilisateur->lastname = $request->lastname;
        $utilisateur->firstname = $request->firstname;
        $utilisateur->gender = $request->gender;
        $utilisateur->phone = $request->phone;
        $utilisateur->address = $request->address;
        $utilisateur->role = $request->role;

        // Mise à jour du mot de passe seulement si un nouveau est renseigné
        if ($request->filled('password')) {
            $utilisateur->password = Hash::make($request->password);
        }

        // Mise à jour de l'image si une nouvelle est envoyée
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public'); // Stocke dans storage/app/public/users
            $utilisateur->image = $imagePath;
        }

        $utilisateur->save();

        // Mise à jour des informations du ressortissant si l'utilisateur a ce rôle
        if ($utilisateur->role === 'ressortissant') {
            $request->validate([
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

            // Vérifier si un ressortissant existe déjà pour cet utilisateur
            $ressortissant = Ressortissant::where('userId', $utilisateur->id)->first();

            if ($ressortissant) {
                // Mise à jour des données existantes
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
            } else {
                // Création d'un nouveau ressortissant si inexistant
                Ressortissant::create([
                    'userId' => $utilisateur->id,
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
            }
        }

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        // Suppression de l'utilisateur
        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur supprimé avec succès');
    }



}
