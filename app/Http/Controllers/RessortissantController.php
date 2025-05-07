<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Ressortissant;
use App\Models\Paiement;
use App\Models\Cotisation;
use App\Models\Lettre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RessortissantController extends Controller
{
    public function index()
    {
        $ressortissant = auth()->user()->ressortissant;
        $paiements = $ressortissant->paiements()->with('typeCotisation')->latest()->get();
        $status = $ressortissant->contributionStatus();
        $lettres = Lettre::where('ressortissant_id', $ressortissant->id)->latest()->get();

        return view('ressortissant.dashboard', compact('ressortissant', 'paiements', 'status', 'lettres'));
    }

    public function downloadReceipt($paiementId)
    {
        $ressortissant = auth()->user()->ressortissant;
        $paiement = Paiement::where('id', $paiementId)
            ->where('ressortissant_id', $ressortissant->id)
            ->with('typeCotisation')
            ->firstOrFail();

        $pdf = Pdf::loadView('ressortissant.receipt', compact('paiement', 'ressortissant'));
        return $pdf->download('recu_paiement_' . $paiement->id . '_' . now()->format('Ymd') . '.pdf');
    }

    public function paiements()
    {
        $ressortissant = auth()->user()->ressortissant;
        $paiements = Paiement::where('ressortissant_id', $ressortissant->id)->latest()->get();
        return view('ressortissant.paiements', compact('paiements'));
    }

    public function statuts()
    {
        $cotisations = $this->getCotisationStatus(auth()->id());
        return view('ressortissant.statuts', compact('cotisations'));
    }


    public function lettres()
    {
        $ressortissant = auth()->user()->ressortissant;
        $lettres = Lettre::where('ressortissant_id', $ressortissant->id)->latest()->get();
        return view('ressortissant.lettre.index', compact('lettres'));
    }

    private function getCotisationStatus($userId)
    {
        $currentYear = now()->year;
        $cotisations = [];

        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            $paid = Paiement::where('ressortissant_id', $userId)->whereYear('created_at', $i)->exists();
            $cotisations[] = [
                'year' => $i,
                'status' => $paid ? 'Payé' : 'Non payé',
            ];
        }

        return $cotisations;
    }

    public function ressortissants()
    {
        $userId = Auth::id();

        // Récupérer les informations du ressortissant associé à l'utilisateur connecté
        $ressortissant = DB::table('ressortissants')
            ->join('utilisateurs', 'ressortissants.userId', '=', 'utilisateurs.id')
            ->where('utilisateurs.id', $userId) // <<< Filtrer seulement l'utilisateur connecté
            ->select(
                'ressortissants.*',
                'utilisateurs.firstname',
                'utilisateurs.lastname',
                'utilisateurs.username',
                'utilisateurs.phone',
                'utilisateurs.address',
                'utilisateurs.role'
            )
            ->first(); // <<< Un seul résultat
    
        return view('ressortissant/compte', compact('ressortissant'));
    }
    
    public function getRessortissant($id)
    {
        // Trouver le ressortissant ou retourner une erreur 404
        $ressortissant = Ressortissant::findOrFail($id);

        // Récupérer l'utilisateur associé (s'il existe)
        $ressortissant = Ressortissant::with('utilisateur')->findOrFail($id);

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


    public function edit()
    {
        $user = auth()->user();
        return view('compteEdit', compact('user'));
    }


    
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nom' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'titre' => 'nullable|string|max:255',
            'raisonSociale' => 'nullable|string|max:255',
            'formejuridique' => 'nullable|string|max:255',
            'secteurActivite' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $user->update($request->only([
            'nom',
            'username',
            'titre',
            'raisonSociale',
            'formejuridique',
            'secteurActivite',
            'phone',
            'adresse',
        ]));

        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }


}
