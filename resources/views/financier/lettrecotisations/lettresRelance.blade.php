<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lettre de Relance</title>
</head>
<body>
    <h1>Lettre de Relance</h1>
    <p>Cher(e) {{ $ressortissant->raisonSociale ?? 'Ressortissant #' . $ressortissant->id }},</p>
    <p>{{ $message }}</p>
    <p>{{ $fichier}}</p>
    <p>Cordialement,<br>Chambre de Commerce et d'Industrie du Niger</p>
</body>
</html>