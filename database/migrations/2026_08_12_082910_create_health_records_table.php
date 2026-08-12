<?php

declare(strict_types=1);

use App\Enums\HealthRecordType;
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
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();

            $table->dateTime('recorded_at');
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['patient_id', 'type']);
            $table->index(['patient_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
