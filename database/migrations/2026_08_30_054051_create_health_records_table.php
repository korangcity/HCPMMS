<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('chronic_disease_id')
                ->nullable()
                ->constrained('chronic_diseases')
                ->nullOnDelete();

            $table->foreignId('recorded_by')
                ->constrained('users')
                ->restrictOnDelete();

          $table->json('measurement_information')->nullable();

            $table->text('description')
                ->nullable();

            $table->timestamps();



        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
