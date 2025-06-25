<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegmentFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segment_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->constrained()->onDelete('cascade'); 
            $table->string('operator')->nullable(); 
            $table->string('filter_type')->nullable(); 
            $table->string('entity_type')->nullable(); 
            $table->unsignedBigInteger('entity_id')->nullable(); 
            $table->json('property_filter')->nullable(); 
            $table->integer('group')->nullable(); 
            $table->unsignedBigInteger('filter_type_option_id')->nullable()->after('group');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segment_filters');
    }
}