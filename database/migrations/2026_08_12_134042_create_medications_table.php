<?php

use App\Enums\MedicationForm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table): void {
            $table->id();

            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('brand_name')->nullable();

            $table->string('form')
                ->default(MedicationForm::Tablet->value);

            $table->string('strength')->nullable();
            $table->string('manufacturer')->nullable();

            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['name', 'is_active']);
            $table->index('generic_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
