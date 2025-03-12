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
            $table->string('titre2')->nullable();
            $table->string('raisonSocial');
            $table->string('formJuri');
            $table->string('rccm')->unique();
            $table->decimal('capitalSocial', 15, 2);
            $table->string('secteurActi');
            $table->string('promoteur');
            $table->integer('dureeCrea');
            $table->foreignId('userId')->constrained('utilisateurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ressortissants');
    }
};
