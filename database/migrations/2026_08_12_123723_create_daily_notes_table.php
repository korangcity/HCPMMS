<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_notes', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('note_date');

            $table->text('content');

            $table->json('metadata')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'patient_id',
                'note_date',
                'created_by',
            ]);

            $table->index([
                'patient_id',
                'note_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_notes');
    }
};
