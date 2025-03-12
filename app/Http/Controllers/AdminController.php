<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
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

        return response()->json([
            'firstname' => $utilisateur->firstname,
            'lastname' => $utilisateur->lastname,
            'phone' => $utilisateur->phone,
            'gender' => $utilisateur->gender,
            'address' => $utilisateur->address,
            'username' => $utilisateur->username,
            'role' => $utilisateur->role,
            'image' => $utilisateur->image ? asset('storage/' . $utilisateur->image) : asset('/images/profile.png'),
        ]);
    }

    public function editUser($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        return view('admin/utilisateurs/editUser', compact('utilisateur'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:20',
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
            'phone' => 'nullable|string|max:20',
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
