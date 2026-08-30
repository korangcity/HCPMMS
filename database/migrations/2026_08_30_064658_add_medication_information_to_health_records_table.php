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
            $table->json('medication_information')
                ->nullable()
                ->after('measurement_information');
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table): void {
            $table->dropColumn('medication_information');
        });
    }
};
