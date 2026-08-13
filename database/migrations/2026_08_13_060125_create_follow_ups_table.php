<?php

declare(strict_types=1);

use App\Enums\FollowUpStatus;
use App\Enums\FollowUpType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('visit_id')
                ->constrained('visits')
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->restrictOnDelete();

            $table->string('type')
                ->default(FollowUpType::Appointment->value);

            $table->string('status')
                ->default(FollowUpStatus::Pending->value);

            $table->dateTime('due_at');

            $table->string('title');

            $table->text('instructions')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->text('completion_notes')->nullable();

            $table->timestamp('notified_at')->nullable();

            $table->timestamps();

            $table->index([
                'patient_id',
                'status',
                'due_at',
            ]);

            $table->index([
                'doctor_id',
                'status',
                'due_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
