<?php

declare(strict_types=1);

use App\Enums\AlertStatus;
use App\Enums\AlertType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('vital_sign_id')
                ->nullable()
                ->constrained('vital_signs')
                ->nullOnDelete();

            $table->foreignId('alert_rule_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('type')
                ->default(AlertType::AbnormalVitalSign->value);

            $table->string('priority');

            $table->string('status')
                ->default(AlertStatus::Open->value);

            $table->string('title');

            $table->text('message');

            $table->decimal('observed_value', 10, 2)
                ->nullable();

            $table->decimal('expected_min', 10, 2)
                ->nullable();

            $table->decimal('expected_max', 10, 2)
                ->nullable();

            $table->string('unit')
                ->nullable();

            $table->timestamp('triggered_at');

            $table->timestamp('acknowledged_at')
                ->nullable();

            $table->foreignId('acknowledged_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('resolved_at')
                ->nullable();

            $table->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('resolution_note')
                ->nullable();

            $table->string('deduplication_key')
                ->nullable();

            $table->timestamps();

            $table->index([
                'patient_id',
                'status',
            ]);

            $table->index([
                'priority',
                'status',
            ]);

            $table->index('triggered_at');

            $table->index('deduplication_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
