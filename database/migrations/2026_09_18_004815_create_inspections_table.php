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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->enum('type', ['active', 'dengue_breeding_site', 'report']);
            $table->enum('status', ['draft', 'queued', 'processing', 'completed', 'failed']);
            $table->boolean('dengue_breeding_site_spotted');
            $table->timestamp('requested_at');
            $table->foreignId('requested_by')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
