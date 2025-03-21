<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showResetPasswordForm(Request $request)
    {
        $username = $request->query('username');
        return view('resetPassword', compact('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Déconnecte l'utilisateur

        $request->session()->invalidate(); // Invalide la session actuelle
        $request->session()->regenerateToken(); // Regénère le jeton CSRF

        return redirect('/'); // Redirige vers la page d'accueil ou une autre page
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $utilisateur = Utilisateur::where('username', $request->username)->first();

        if ($utilisateur) {
            $utilisateur->password = Hash::make($request->password);
            $utilisateur->first_login = false; // Désactiver le flag après la réinitialisation
            $utilisateur->save();

            return redirect()->route('login')->with('success', 'Mot de passe mis à jour avec succès.');
        }

        return redirect()->route('login')->withErrors(['error' => 'Utilisateur non trouvé.']);
    }

    public function login(Request $request)
    {
        // Validation des champs requis
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Vérifier si l'utilisateur existe
        $utilisateur = Utilisateur::where('username', $request->username)->first();
        // Vérifier si c'est la première connexion
        if ($utilisateur->first_login) {
            return redirect()->route('password.reset', ['username' => $utilisateur->username])
                ->with('info', 'Veuillez réinitialiser votre mot de passe.');
        }

        // Tentative d'authentification
        if (Auth::attempt($credentials)) {
            // Récupération de l'utilisateur connecté
            $user = Auth::user();
            // Vérification de l'utilisateur authentifié
            if ($user) {
                // Stocker l'utilisateur dans la session si nécessaire
                session()->put('user', $user);

                

                // Redirection selon le rôle de l'utilisateur
                return match ($user->role) {
                    'admin' => redirect()->route('admin.dashboard')->with('success', 'Bienvenue Admin !'),
                    'ressortissant' => redirect()->route('ressortissant.dashboard')->with('success', 'Bienvenue Ressortissant !'),
                    'caissier' => redirect()->route('caissier.dashboard')->with('success', 'Bienvenue Caissier !'),
                    'financier' => redirect()->route('financier.dashboard')->with('success', 'Bienvenue Financier !'),
                    default => redirect('/')->withErrors(['login' => 'Rôle inconnu, veuillez contacter l\'administration.']),
                };
            }
        }

        // Authentification échouée
        return back()->withErrors(['login' => 'Nom d\'utilisateur ou mot de passe incorrect.'])->withInput();
    }

}
