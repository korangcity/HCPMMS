<?php

use App\Enums\DoseUnit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('prescription_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('medication_id')
                ->constrained()
                ->restrictOnDelete();

            $table->decimal('dose', 10, 2);

            $table->string('dose_unit')
                ->default(DoseUnit::Tablet->value);

            $table->string('route')->nullable();

            $table->unsignedInteger('quantity')->nullable();

            $table->unsignedInteger('duration_days')->nullable();

            $table->text('instructions')->nullable();

            $table->timestamps();

            $table->index([
                'prescription_id',
                'medication_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
