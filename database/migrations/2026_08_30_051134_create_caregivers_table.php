<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caregivers', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('relationship')
                ->nullable();

            $table->text('address')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->string('status', 20)
                ->default('active')
                ->index();

            $table->timestamps();
        });
        Schema::create('caregiver_patient', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('caregiver_id')
                ->constrained('caregivers')
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->boolean('is_primary')
                ->default(false);

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'caregiver_id',
                'patient_id',
            ]);

            $table->index('caregiver_id');
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_patient');
        Schema::dropIfExists('caregivers');
    }
};
