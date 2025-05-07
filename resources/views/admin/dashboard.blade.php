@extends('layouts.app')

@section('title', 'Tableau de Bord Admin')

@section('content')

<h1 class="mt-5">Dashboard Admin</h1>
  
    <div class="container-fluid">
        
                   
            <div class="scroolAsignSubj h-100 px-4 pt-5 haut-rendbody">
                <div class="d-flex flex-column py-2 px-4 my-md-4 my-3 " style="height: 90vh;">
                    <div class="d-flex flex-md-row flex-column justify-content-between mb-md-5 mb-0 ">
                        <div class="col-md-4 col-12 mb-4 mb-md-0 card border-1 border-black shadow p-3 rounded">
                            <div class="card-body">
                                <h5 class="card-title">Nombre d' utilisateurs</h4>
                                <div class="text-center text-4xl font-semibold text-blue-600">
                                <p class="text-2xl font-bold text-blue-600">{{ $nombreUtilisateurs }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-4 mb-md-0 card border-1 border-black shadow p-3 rounded">
                            <div class="card-body">
                                <h5 class="card-title">Nombre de Ressortissants</h4>
                                <p class="text-2xl font-bold text-pink-600">{{ $nombreRessortissants }}</p>
                                <div class="text-end">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mb-4 mb-md-0 card border-1 border-black shadow p-3 rounded">
                            <div class="card-body">
                                <h5 class="card-title">Nombre de cotisation payé</h4>
                                <p class="text-2xl font-bold text-pink-600">{{ $nombrePaiements }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-md-row flex-column justify-content-between">
                        <div class="col-md-7 col-12 card border-1 border-black shadow p-3 rounded">
                            <h2 class="text-2xl font-bold mb-4">Évolution des utilisateurs et ressortissants</h2>
                            <canvas id="comparaisonChart" width="400" height="200"></canvas>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <script>
                                const moisLabels = {!! json_encode($moisLabels) !!};
                                const utilisateurs = {!! json_encode($utilisateurs) !!};
                                const ressortissants = {!! json_encode($ressortissants) !!};

                                const ctx = document.getElementById('comparaisonChart').getContext('2d');
                                const comparaisonChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: moisLabels.map(m => 'Mois ' + m),
                                        datasets: [
                                            {
                                                label: 'Utilisateurs',
                                                data: utilisateurs,
                                                borderColor: 'rgba(54, 162, 235, 1)',
                                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                fill: false,
                                                tension: 0.1
                                            },
                                            {
                                                label: 'Ressortissants',
                                                data: ressortissants,
                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                fill: false,
                                                tension: 0.1
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                        <div class="col-md-4 col-12 mt-4 mt-md-0 card border-1 border-black shadow p-3 rounded">
                    <div class="card-body">
                        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
                        @forelse ($notifications as $notification)
                            <p>{{ $notification->message }} ({{ $notification->created_at->format('d/m/Y') }})</p>
                        @empty
                            <p>Aucune notification récente.</p>
                        @endforelse
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
