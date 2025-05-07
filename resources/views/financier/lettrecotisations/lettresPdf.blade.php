<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lettre de Cotisation</title>
</head>
<body>
    <h1>Lettre de Cotisation</h1>
    <p>Cher(e) {{ $ressortissant->raisonSociale ?? }},</p>
    <p>{{ $message }}</p>
    <p>{{ $fichier}}</p>
    <p>Cordialement,<br>Chambre de Commerce et d'Industrie du Niger</p>
</body>
</html>