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
                ->restrictOnDelete();

            $table->dateTime('scheduled_at');

            $table->unsignedSmallInteger('duration_minutes')
                ->default(30);

            $table->string('status')
                ->default(AppointmentStatus::Scheduled->value);

            $table->string('reason')->nullable();

            $table->text('patient_note')->nullable();

            $table->text('cancellation_reason')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index([
                'doctor_id',
                'scheduled_at',
                'status',
            ]);

            $table->index([
                'patient_id',
                'scheduled_at',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
