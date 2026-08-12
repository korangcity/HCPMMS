<?php

use App\Enums\MedicationScheduleFrequency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_schedules', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('prescription_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('frequency')
                ->default(MedicationScheduleFrequency::OnceDaily->value);

            $table->time('scheduled_time');

            $table->unsignedSmallInteger('interval_hours')->nullable();

            $table->date('starts_at');
            $table->date('ends_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'prescription_item_id',
                'is_active',
            ]);

            $table->index([
                'starts_at',
                'ends_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_schedules');
    }
};
