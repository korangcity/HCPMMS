<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();


            $table->date('birth_date')
                ->nullable();

            $table->string('blood_type', 10)
                ->nullable();

            $table->string('emergency_contact_name')
                ->nullable();

            $table->string('emergency_contact_phone', 30)
                ->nullable();

            $table->text('address')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->string('status', 20)
                ->default('active')
                ->index();

            $table->timestamps();

            $table->index('birth_date');
        });

        Schema::create('doctor_patient', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->timestamp('assigned_at')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'doctor_id',
                'patient_id',
            ]);

            $table->index('doctor_id');
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_patient');
        Schema::dropIfExists('patients');
    }
};
