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
            // Add cotisation_id if it doesn't exist
            if (!Schema::hasColumn('paiements', 'cotisation_id')) {
                $table->foreignId('cotisation_id')->nullable(false)->change();
            }

            // Add reference if it doesn't exist
            if (!Schema::hasColumn('paiements', 'reference')) {
                $table->string('reference')->default('TEMP')->after('cotisation_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            if (Schema::hasColumn('paiements', 'cotisation_id')) {
                $table->dropColumn('cotisation_id');
            }
            if (Schema::hasColumn('paiements', 'reference')) {
                $table->dropColumn('reference');
            }
        });
    
    }
};
