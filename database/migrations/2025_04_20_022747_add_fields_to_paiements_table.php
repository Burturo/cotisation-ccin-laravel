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
        Schema::table('paiements', function (Blueprint $table) {
           // $table->unsignedBigInteger('cotisation_id')->after('ressortissant_id');
            $table->string('reference')->after('cotisation_id');

            // Clés étrangères (optionnel mais conseillé)
            
           // $table->foreign('cotisation_id')->references('id')->on('cotisations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            
            $table->dropColumn([  'reference']);
        });
    }
};
