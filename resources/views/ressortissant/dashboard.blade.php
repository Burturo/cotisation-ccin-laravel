@extends('layouts.app')

@section('title', 'Tableau de Bord Ressortissant')

@section('content')

<head>
    <title>Tableau de Bord Ressortissant</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .status-up-to-date { color: green; }
        .status-pending { color: orange; }
        .status-overdue { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tableau de Bord - {{ $ressortissant->raisonSociale }}</h1>

        <!-- Contribution Status -->
        
        <div class="card shadow mb-4">
        
            <div class="card-header ">
            <h6>Statut des Cotisations</h6>
            </div>
            <div class="card-body">
                <p class="status-{{ $status['status'] }}">{{ $status['message'] }}</p>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">Historique des Paiements</div>
            <body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-6">
        @include('partials.sidebar')
        <h4 class="text-3xl font-bold text-gray-800 mb-6">Mes Paiements</h4>
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if ($paiements->isEmpty())
            <p class="text-gray-600">Aucun paiement trouvé.</p>
        @else
            <div class="table-container px-2">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="table-light">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de Cotisation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Paiement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($paiements as $paiement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $paiement->cotisation->typeCotisation->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($paiement->montant, 2) }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $paiement->date_paiement->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('paiement.recu', $paiement->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-download"></i> Télécharger Reçu
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
        </div>

        <!-- Documents -->
        <div class="card shadow">
        <div class="card-header bg-secondary text-white">Documents Reçus</div>
            <div class="card-body">
                @if ($lettres->isEmpty())
                    <p>Aucun document reçu.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    @foreach ($lettres as $lettre)
                        <tr>
                            <td>{{ $lettre->title }}</td>
                            <td>{{ $lettre->type }}</td>
                            <td>{{ $lettre->date_envoi ? $lettre->date_envoi->format('d/m/Y') : 'Non envoyée' }}
                            </td>
                            <td>
                            <a href="{{ asset('storage/' . $lettre->file_path) }}" download>Télécharger le document</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</body>
    
@endsection
