@extends('layouts.app')

@section('title', 'Tableau de Bord Caissier')

@section('content')


<div class="container-fluid card mt-5 p-4">
    <h5>Dashboard Caissier</h5>
    <div class="scroolAsignSubj h-100 px-4 haut-rendbody">
        <div class="d-flex flex-column py-2 px-4 my-md-4 my-3" style="height: 90vh;">
            <div class="d-flex flex-md-row flex-column justify-content-between mb-md-5 mb-0">
                <div class="col-md-6 col-12 mb-4 mb-md-0 me-md-3 me-0 card border-1 border-black shadow p-3 rounded">
                    <div class="card-body">
                        <h5 class="card-title">Nombre de Ressortissants</h5>
                        <p class="card-text fs-5">{{ $nombreRessortissants }}</p>
                        <div class="text-end">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12 mb-4 mb-md-0 ms-md-3 ms-0 card border-1 border-black shadow p-3 rounded">
                    <div class="card-body">
                        <h5 class="card-title">Nombre de paiements effectués</h5>
                        <p class="card-text fs-5">{{ $nombrePaiements }}</p>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-md-row flex-column justify-content-between">
                <div class="col-12 mt-4 mt-md-0 card border-1 border-black shadow p-3 rounded">
                <div class="container">    
                <h5 class="card-title">Evolution des paiements</h5>
                    <canvas id="evolutionPaiements" height="100"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
                        <script>
                            // Ensure Chart.js is loaded
                            if (typeof Chart === 'undefined') {
                                console.error('Chart.js n\'est pas chargé. Vérifiez le chargement du script.');
                            } else {
                                console.log('Chart.js est chargé avec succès.');
                            }

                            const ctx = document.getElementById('evolutionPaiements').getContext('2d');
                            const evolutionPaiements = @json($evolutionPaiements);

                            // Debug: Log the data to the console
                            console.log('Données pour le graphique :', evolutionPaiements);

                            // Validate data
                            if (!evolutionPaiements || evolutionPaiements.length === 0 || !evolutionPaiements.every(item => item.mois && item.total !== undefined)) {
                                console.warn('Données invalides ou absentes pour le graphique.');
                                document.getElementById('evolutionPaiements').style.display = 'none';
                                const cardBody = document.getElementById('evolutionPaiements').parentElement;
                                cardBody.innerHTML += '<p>Aucune donnée valide disponible pour afficher le graphique.</p>';
                            } else {
                                console.log('Création du graphique avec les données :', evolutionPaiements);
                                new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: evolutionPaiements.map(item => item.mois),
                                        datasets: [{
                                            label: 'Paiements',
                                            data: evolutionPaiements.map(item => item.total),
                                            borderColor: 'blue',
                                            fill: false,
                                            tension: 0.1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Nombre de paiements'
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Mois'
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        </script>
                         </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

</div>
@endsection