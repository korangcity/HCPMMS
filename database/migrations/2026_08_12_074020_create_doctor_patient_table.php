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
        Schema::create('doctor_patient', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table
                ->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            $table->unique(
                ['doctor_id', 'patient_id'],
                'doctor_patient_unique'
            );

            $table->index(
                ['patient_id', 'is_primary'],
                'patient_primary_doctor_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_patient');
    }
};
