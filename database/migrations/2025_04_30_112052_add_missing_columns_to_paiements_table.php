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
    Schema::table('paiements', function (Blueprint $table) {
        if (!Schema::hasColumn('paiements', 'reference')) {
            $table->string('reference')->nullable();
        }

        if (!Schema::hasColumn('paiements', 'cotisation_id')) {
            $table->unsignedBigInteger('cotisation_id')->nullable()->after('ressortissant_id');
            $table->foreign('cotisation_id')->references('id')->on('cotisations')->onDelete('set null');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['cotisation_id']);
            $table->dropColumn(['reference', 'cotisation_id']);
        });
    }
};
