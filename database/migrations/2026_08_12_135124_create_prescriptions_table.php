<?php

use App\Enums\PrescriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('status')
                ->default(PrescriptionStatus::Draft->value);

            $table->date('prescribed_at');

            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['patient_id', 'status']);
            $table->index(['doctor_id', 'status']);
            $table->index('prescribed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
