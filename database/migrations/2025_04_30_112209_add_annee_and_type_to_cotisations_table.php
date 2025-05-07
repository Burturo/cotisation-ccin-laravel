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
        Schema::table('cotisations', function (Blueprint $table) {
            if (!Schema::hasColumn('cotisations', 'annee')) {
                $table->integer('annee')->nullable();
            }

            if (!Schema::hasColumn('cotisations', 'type_cotisation_id')) {
                $table->unsignedBigInteger('type_cotisation_id')->nullable()->after('annee');
                $table->foreign('type_cotisation_id')->references('id')->on('type_cotisations')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('cotisations', function (Blueprint $table) {
            if (Schema::hasColumn('cotisations', 'annee')) {
                $table->dropColumn('annee');
            }

            if (Schema::hasColumn('cotisations', 'type_cotisation_id')) {
                $table->dropForeign(['type_cotisation_id']);
                $table->dropColumn('type_cotisation_id');
            }
        });
    }
};
