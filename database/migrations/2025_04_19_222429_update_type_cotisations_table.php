<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_cotisations', function (Blueprint $table) {
            // Revenir en arrière : renommer montant en montant_fixe
            $table->renameColumn('montant', 'montant_fixe');

            // Supprimer la colonne formeJuridique
            $table->dropColumn('formeJuridique');

            // Recréer la colonne formejuridique (si nécessaire)
            $table->string('formejuridique', 255)->nullable();
        });
    
    }
};
