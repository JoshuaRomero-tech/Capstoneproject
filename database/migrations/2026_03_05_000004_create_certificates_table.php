<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
            $table->enum('type', [
                'Barangay Clearance',
                'Certificate of Residency',
                'Certificate of Indigency',
                'Business Clearance',
                'Barangay ID',
            ]);
            $table->string('purpose');
            $table->string('or_number')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('date_issued');
            $table->date('valid_until')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
