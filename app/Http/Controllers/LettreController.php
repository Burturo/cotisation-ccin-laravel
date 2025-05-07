<?php

namespace App\Http\Controllers;

use App\Models\Ressortissant;
use App\Models\Lettre;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;



class LettreController extends Controller
{

    public function create()
    {
        $ressortissants = Ressortissant::orderBy('raisonSociale')->get();
        return view('financier.lettrecotisations.create', compact('ressortissants'));
    }
    public function store(Request $request)
    {
        // Validation des champs requis
        $request->validate([
            'ressortissant_id' => 'required|exists:ressortissants,id',
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10 Mo
        ]);
    
        // Enregistrement du fichier
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $filePath = $request->file('fichier')->store('lettres', 'public');
    
            Log::info('Fichier stocké à : ' . $filePath);
        } else {
            return back()->with('error', 'Aucun fichier n\'a été téléchargé.');
        }
    
        // Création de la lettre
        $lettre = new Lettre();
        $lettre->ressortissant_id = $request->ressortissant_id;
        $lettre->utilisateur_id = auth()->id();
        $lettre->type = $request->type;
        $lettre->title = $request->title;
        $lettre->description = $request->description;
        $lettre->file_path = $filePath;
        $lettre->date_envoi = now();
        $lettre->save();
       // dd($lettre->toArray());
    
        Log::info('Lettre enregistrée avec succès', ['lettre_id' => $lettre->id]);
    
        return redirect()->route('financier.lettrecotisations.index')->with('success', 'Lettre envoyée avec succès.');

    }
    public function index()
    {
        $lettres = Lettre::with('ressortissant', 'utilisateur')->latest()->get();
        \Log::info('LettreController::index', ['lettres_count' => $lettres->count()]);
        return view('financier.lettrecotisations.index', compact('lettres'));
    }

    public function markAsReceived(Lettre $lettre)
    {
        $lettre->update(['recu' => true]);
        return redirect()->route('financier.lettrecotisations.index')
            ->with('success', 'Lettre marquée comme reçue.');
    }

    public function markAsPaid(Lettre $lettre)
    {
        $lettre->update(['paye_apres_envoi' => true]);
        return redirect()->route('financier.lettrecotisations.index')
            ->with('success', 'Paiement confirmé.');
    }

    // Envoyer une lettre de relance
    public function sendRelance(Lettre $lettre)
    {
        // Vérifier que la lettre n'a pas déjà été relancée et que le ressortissant n'a pas payé
        if ($lettre->relance_envoyee || $lettre->paye_apres_envoi) {
            return redirect()->route('financier.lettrecotisations.index')->with('error', 'Relance non applicable.');
        }

        // Générer un PDF pour la relance
        $ressortissant = $lettre->ressortissant;
        $pdf = Pdf::loadView('financier.lettreCotisations.lettresRelance', [
            'ressortissant' => $ressortissant,
            'message' => 'Ceci est une relance pour le paiement de votre cotisation. Veuillez régler au plus vite.',
            
        ]);
        return $pdf->download('relance_' . now()->format('Ymd') . '.pdf');
        // Sauvegarder le PDF de relance
        //$fichierRelance = 'lettres/relance_' . $ressortissant->id . '_' . time() . '.pdf';
        //$pdf->save(storage_path('app/public/' . $fichierRelance));

        // Mettre à jour la lettre
        $lettre->update([
            'fichier' => $fichierRelance,
            'relance_envoyee' => true,
        ]);

        return redirect()->route('financier.lettrecotisations.index')->with('success', 'Lettre de relance envoyée.');
    }
}