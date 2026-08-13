<?php

declare(strict_types=1);

use App\Enums\AlertPriority;
use App\Enums\VitalSignType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alert_rules', function (Blueprint $table): void {
            $table->id();

            $table->string('name');

            $table->string('vital_type');

            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();

            $table->string('priority')
                ->default(AlertPriority::Medium->value);

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedInteger('deduplication_minutes')
                ->default(30);

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index([
                'vital_type',
                'is_active',
            ]);

            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_rules');
    }
};
