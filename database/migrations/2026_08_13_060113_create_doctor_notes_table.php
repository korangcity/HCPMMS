<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_notes', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('visit_id')
                ->constrained('visits')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->restrictOnDelete();

            $table->string('title')->nullable();

            $table->longText('content');

            $table->boolean('is_private')
                ->default(false);

            $table->timestamps();

            $table->index([
                'visit_id',
                'doctor_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_notes');
    }
};
