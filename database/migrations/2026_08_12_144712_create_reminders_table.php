<?php

declare(strict_types=1);

use App\Enums\ReminderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('medication_id')
                ->nullable()
                ->constrained('medications')
                ->nullOnDelete();

            $table->string('type', 30);

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->timestamp('scheduled_at');

            $table->timestamp('completed_at')
                ->nullable();

            $table->string('status', 30)
                ->default(ReminderStatus::Pending->value);

            $table->timestamp('notified_at')
                ->nullable();

            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'patient_id',
                'status',
                'scheduled_at',
            ]);

            $table->index([
                'status',
                'scheduled_at',
            ]);

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
