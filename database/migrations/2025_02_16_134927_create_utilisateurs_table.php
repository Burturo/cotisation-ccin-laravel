<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->enum('role', ['admin', 'ressortissant', 'caissier', 'financier'])->default('ressortissant');
            $table->string('image')->nullable();
            $table->timestamps();
        });
 
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('utilisateurs');
       Schema::dropIfExists('sessions');
   }
};
