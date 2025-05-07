<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lettres', function (Blueprint $table) {
            $table->foreignId('ressortissant_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('utilisateur_id')->constrained()->onDelete('cascade')->after('ressortissant_id');
            $table->string('type')->after('utilisateur_id');
            $table->string('file_path')->after('type');
            $table->string('title')->after('file_path');
            $table->text('description')->nullable()->after('title');
            $table->timestamp('date_envoi')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('lettres', function (Blueprint $table) {
            $table->dropColumn([
                'ressortissant_id',
                'utilisateur_id',
                'type',
                'file_path',
                'title',
                'description',
                'sent_at',
            ]);
        });
    
    }
};
