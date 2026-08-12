<?php

declare(strict_types=1);

namespace App\Services\HealthMonitoring;

use App\Events\DailyNoteCreated;
use App\Models\DailyNote;
use Illuminate\Support\Facades\DB;

final class DailyNoteService
{
    /**
     * @param array{
     *     patient_id:int,
     *     created_by?:int|null,
     *     note_date:\DateTimeInterface,
     *     content:string,
     *     metadata?:array<string,mixed>|null
     * } $data
     */
    public function create(array $data): DailyNote
    {
        return DB::transaction(function () use ($data): DailyNote {
            $note = DailyNote::query()->create([
                'patient_id' => $data['patient_id'],
                'created_by' => $data['created_by'] ?? null,
                'note_date' => $data['note_date'],
                'content' => $data['content'],
                'metadata' => $data['metadata'] ?? null,
            ]);

            DailyNoteCreated::dispatch($note);

            return $note;
        });
    }
}
