<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('distribution_name')->nullable();
            $table->string('status')->default('pending');

            $table->unsignedTinyInteger('distribution');
            $table->unsignedTinyInteger('consumer_source');
            $table->unsignedTinyInteger('contact_type');

            $table->foreignId('segment_id')->nullable()->constrained('segments')->nullOnDelete();
            $table->foreignId('behavior_done_id')->nullable()->constrained('behaviors')->nullOnDelete();
            $table->foreignId('behavior_not_done_id')->nullable()->constrained('behaviors')->nullOnDelete();

            $table->dateTime('init_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->integer('global_interval')->nullable();
            $table->integer('available_until')->nullable(); 
            $table->integer('next_impact')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
