<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            $table->string('case_no')->unique();
            $table->foreignId('complainant_id')->constrained('residents')->cascadeOnDelete();
            $table->foreignId('respondent_id')->constrained('residents')->cascadeOnDelete();
            $table->text('incident_details');
            $table->string('incident_type');
            $table->date('incident_date');
            $table->string('incident_location');
            $table->enum('status', ['Pending', 'Ongoing', 'Resolved', 'Dismissed'])->default('Pending');
            $table->text('action_taken')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotters');
    }
};
