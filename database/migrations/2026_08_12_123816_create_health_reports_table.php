<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_reports', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title', 255);

            $table->date('period_start');

            $table->date('period_end');

            $table->string('status', 30)
                ->default('draft');

            $table->json('summary')
                ->nullable();

            $table->longText('content')
                ->nullable();

            $table->timestamp('generated_at')
                ->nullable();

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'patient_id',
                'period_start',
                'period_end',
            ]);

            $table->index([
                'patient_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_reports');
    }
};
