
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 100px; }
        .signature { width: 150px; margin-top: 30px; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        td, th { padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logoccin.jpg') }}" class="logo">
        <h2>Reçu de Paiement</h2>
    </div>

    <table>
        <tr>
            <td><strong>Raison sociale :</strong></td>
            <td>{{ $paiement->ressortissant->raisonSociale }}</td>
        </tr>
        <tr>
            <td><strong>RCCM :</strong></td>
            <td>{{ $paiement->ressortissant->rccm }}</td>
        </tr>
        <tr>
            <td><strong>Montant :</strong></td>
            <td>{{ number_format($paiement->montant, 2) }} F CFA</td>
        </tr>
        <tr>
            <td><strong>Mode de paiement :</strong></td>
            <td>{{ $paiement->methode_paiement }}</td>
        </tr>
        <tr>
            <td><strong>Date :</strong></td>
            <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Référence :</strong></td>
            <td>{{ $paiement->reference }}</td>
        </tr>
    </table>

    <div style="margin-top: 40px; text-align: right;">
        <p>Signature</p>
        <img src="{{ public_path('images/image.png') }}" class="signature">
    </div>
</body>
</html>


