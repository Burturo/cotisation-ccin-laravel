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
        Schema::create('ressortissants', function (Blueprint $table) {
            $table->id();
            $table->string('titre1');
            $table->string('titre2');
            $table->string('RaisonSocial');
            $table->string('FormJuri');
            $table->string('RCCM')->unique();
            $table->string('CapitalSocial');
            $table->string('SecteurActi');
            $table->string('Promoteur');
            $table->string('Sexe');
            $table->string('DureeCrea');
            $table->string('telephone');
            $table->string('adresse');
            $table->foreignId('utilisateur_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressortissants');
    }
};
