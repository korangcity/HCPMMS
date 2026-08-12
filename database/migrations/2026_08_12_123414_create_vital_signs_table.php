<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vital_signs', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type', 50);

            $table->decimal('value', 10, 2)
                ->nullable();

            $table->decimal('secondary_value', 10, 2)
                ->nullable();

            $table->string('unit', 30);

            $table->timestamp('recorded_at');

            $table->string('source', 50)
                ->default('manual');

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'patient_id',
                'type',
                'recorded_at',
            ]);

            $table->index([
                'patient_id',
                'recorded_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
