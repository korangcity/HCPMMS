<?php

declare(strict_types=1);

use App\Enums\ChronicDiseaseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chronic_diseases', function (Blueprint $table): void {
            $table->id();

            $table->string('name');

            $table->string('status', 20)
                ->default(ChronicDiseaseStatus::Active->value);

            $table->text('description')
                ->nullable();

            $table->timestamps();

        });


    }

    public function down(): void
    {
        Schema::dropIfExists('chronic_diseases');
    }
};
