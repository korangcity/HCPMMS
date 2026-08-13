<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alert_recipients', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('alert_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('recipient_type');

            $table->timestamp('notified_at')
                ->nullable();

            $table->timestamp('read_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'alert_id',
                'user_id',
            ]);

            $table->index([
                'user_id',
                'read_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_recipients');
    }
};
