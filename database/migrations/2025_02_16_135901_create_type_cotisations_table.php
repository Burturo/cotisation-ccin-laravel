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
        Schema::create('type_cotisations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Mensuelle, Annuelle, etc.
            $table->renameColumn('montant_fixe', 'montant', 10, 2);
            $table->text('description')->nullable();
            $table->text('formeJuridique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_cotisations');
    }
};
