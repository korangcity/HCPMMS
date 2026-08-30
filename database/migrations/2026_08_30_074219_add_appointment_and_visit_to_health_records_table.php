<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table): void {
            $table->foreignId('appointment_id')
                ->nullable()
                ->after('recorded_by')
                ->constrained('appointments')
                ->nullOnDelete();

            $table->foreignId('visit_id')
                ->nullable()
                ->after('appointment_id')
                ->constrained('visits')
                ->nullOnDelete();

            $table->index('appointment_id');
            $table->index('visit_id');
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table): void {
            $table->dropForeign([
                'appointment_id',
            ]);

            $table->dropForeign([
                'visit_id',
            ]);

            $table->dropIndex([
                'health_records_appointment_id_index',
            ]);

            $table->dropIndex([
                'health_records_visit_id_index',
            ]);

            $table->dropColumn([
                'appointment_id',
                'visit_id',
            ]);
        });
    }
};
