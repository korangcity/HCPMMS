<?php

declare(strict_types=1);

use App\Enums\AppointmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->dateTime('scheduled_at');

            $table->string('status', 20)
                ->default(AppointmentStatus::Scheduled->value);

            $table->text('description')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
