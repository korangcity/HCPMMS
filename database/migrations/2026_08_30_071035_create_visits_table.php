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

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            $table->dateTime('visited_at');

            $table->text('doctor_notes')
                ->nullable();

            $table->text('diagnosis')
                ->nullable();

            $table->text('treatment_plan')
                ->nullable();

            $table->text('follow_up')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
