<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lettres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ressortissant_id')->constrained('ressortissants')->onDelete('cascade');
            $table->foreignId('utilisateur_id')->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., 'lettre', 'attestation'
            $table->string('file_path'); // Path to the stored file
            $table->string('title'); // Document title
            $table->text('description')->nullable(); // Optional description
            $table->date('date_envoi'); // When the document was sent
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lettres');
    }
};
