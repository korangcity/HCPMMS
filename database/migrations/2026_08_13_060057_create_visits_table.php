<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->restrictOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->restrictOnDelete();

            $table->timestamp('visited_at');

            $table->text('chief_complaint')->nullable();

            $table->text('diagnosis')->nullable();

            $table->text('clinical_summary')->nullable();

            $table->text('treatment_plan')->nullable();

            $table->text('patient_instructions')->nullable();

            $table->text('private_notes')->nullable();

            $table->timestamps();

            $table->unique('appointment_id');

            $table->index([
                'patient_id',
                'visited_at',
            ]);

            $table->index([
                'doctor_id',
                'visited_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
