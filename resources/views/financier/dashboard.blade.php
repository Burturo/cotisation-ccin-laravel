@extends('layouts.app')

@section('title', 'Tableau de Bord Financier')

@section('content')
   
<div class="container-fluid card mt-5 p-4">
    <h5>Dashboard Financier</h5>
    <div class="scroolAsignSubj h-100 px-4 haut-rendbody">
                   
            <div class="scroolAsignSubj h-100 px-4 pt-5 haut-rendbody">
                <div class="d-flex flex-column py-2 px-4 my-md-4 my-3 " style="height: 90vh;">
                    <div class="d-flex flex-md-row flex-column justify-content-between mb-md-5 mb-0 ">
                        <div class="col-md-6 col-12 mb-4 mb-md-0 me-md-3 me-0 card border-1 border-black shadow p-3 rounded">
                            <div class="card-body">
                                <h5 class="card-title">Nombre de Ressortissants</h4>
                                <p class="text-2xl font-bold text-pink-600">{{ $nombreRessortissants }}</p>
                                <div class="text-end">
                                
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12 mb-4 mb-md-0 ms-md-3 ms-0 card border-1 border-black shadow p-3 rounded">
                            <div class="card-body">
                                <h5 class="card-title">Nombre de cotisations payé</h4>
                                <p class="text-2xl font-bold text-pink-600">{{ $nombrePaiements }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-md-row flex-column justify-content-between">
                        <div class="col-md-7 col-13 card border-1 border-black shadow p-3 rounded">
                           
                                <div class="container">
                                    <h1>Tableau de Bord des Cotisations</h1>
                                    <canvas id="cotisationsChart"></canvas>
                                    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const mois = @json($moisLabels);
    const montants = @json($paiements);

    const ctx = document.getElementById('cotisationsChart').getContext('2d');
    const cotisationsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: mois,
            datasets: [{
                label: 'Montant Total des Cotisations',
                data: montants,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mois',
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Montant (CFA)',
                    },
                    beginAtZero: true,
                }
            }
        }
    });
</script>

                     
                                </div>
                        </div>
                        <div class="col-md-4 col-12 mt-4 mt-md-0 card border-1 border-black shadow p-3 rounded">
                            <div class="card-body">
                                <h5 class="card-title border-2 border-bottom border-black">Notifications</h4> 
                                @forelse ($notifications as $notification)
                            <p>{{ $notification->message }} ({{ $notification->created_at->format('d/m/Y') }})</p>
                        @empty
                            <p>Aucune notification récente.</p>
                        @endforelse<!-- Titre de la section de notifications. -->
                                <div class="card-body">
                                    <h6 class="card-title"></h6> <!-- Titre de la carte. -->
                                    <p class="card-text"></p> <!-- Affiche le nombre de courriers non lus. -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

