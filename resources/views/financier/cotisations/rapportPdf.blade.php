<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Ressortissant à jours</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Rapport des Ressortissants à jour </h1>
    <p>Date de génération : {{ now()->format('d/m/Y à H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Raison Sociale</th>
                <th>RCCM</th>
                <th>Forme Juridique</th>
                <th>Secteur d'Activité</th>
                <th>Date Paiement</th>
                
            </tr>
        </thead>
        <tbody>
           
            @foreach($ressortissants as $key => $ressortissant)
                @foreach($ressortissant->paiements as  $paiement)
                    <tr>
                        <td>{{  $key + 1 }}</td>
                        <td>{{ $ressortissant->raisonSociale }}</td>
                        <td>{{ $ressortissant->rccm }}</td>
                        <td>{{ $ressortissant->formeJuridique  }}</td>
                        <td>{{ $ressortissant->secteurActivite }}</td>
                        <td>{{ \Carbon\Carbon::parse($paiement->created_at)->format('d/m/Y') }}</td>
                        
                    </tr>
                   
                @endforeach
            @endforeach
            
        </tbody>
    </table>
</body>
</html>
