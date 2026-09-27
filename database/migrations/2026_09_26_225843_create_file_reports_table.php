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
        Schema::create('file_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')
                ->references('id')
                ->on('files');
            $table->enum('irregularity', ['open_water_tank', 'abandoned_pool']);
            $table->enum('status', ['created', 'provided_feedback']);
            $table->text('agent_report');
            $table->enum('user_feedback', ['correct', 'incorrect'])->nullable();
            $table->foreignId('feedback_by')
                ->nullable()
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
        Schema::dropIfExists('file_reports');
    }
};
