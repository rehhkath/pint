<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Renomeia a coluna 'init_date' para 'start_date'
            $table->renameColumn('init_date', 'start_date');

            // Adiciona as novas colunas 'created_by' e 'updated_by'
            $table->unsignedBigInteger('created_by')->nullable()->after('next_impact');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Reverte o nome da coluna 'start_date' para 'init_date'
            $table->renameColumn('start_date', 'init_date');

            // Remove as colunas 'created_by' e 'updated_by'
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}