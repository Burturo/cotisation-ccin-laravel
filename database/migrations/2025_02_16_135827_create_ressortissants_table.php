<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ressortissants', function (Blueprint $table) {
            $table->id();
            $table->string('titre1');
            $table->string('titre2');
            $table->string('raisonSociale');
            $table->string('formeJuridique');
            $table->string('rccm')->unique();
            $table->decimal('capitalSociale', 25, 2)->nullable(); // Permet null
            $table->decimal('cotisationAnnuelle', 25, 2)->nullable(); // Permet null
            $table->string('secteurActivite');
            $table->string('promoteur')->nullable(); // Permet null
            $table->string('dureeCreation')->nullable();
            $table->string('localiteEtRegion');
            $table->foreignId('userId')->constrained('utilisateurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ressortissants');
    }
};
