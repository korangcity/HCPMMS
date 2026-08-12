<?php

declare(strict_types=1);

namespace App\Services\HealthMonitoring;

use App\Enums\HealthRecordType;
use App\Events\HealthRecordCreated;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\DB;

final class HealthRecordService
{
    /**
     * @param array{
     *     patient_id:int,
     *     recorded_by?:int|null,
     *     type:HealthRecordType|string,
     *     title:string,
     *     description?:string|null,
     *     data?:array<string,mixed>|null,
     *     recorded_at:\DateTimeInterface
     * } $data
     */
    public function create(array $data): HealthRecord
    {
        return DB::transaction(function () use ($data): HealthRecord {
            $type = $data['type'] instanceof HealthRecordType
                ? $data['type']
                : HealthRecordType::from($data['type']);

            $record = HealthRecord::query()->create([
                'patient_id' => $data['patient_id'],
                'recorded_by' => $data['recorded_by'] ?? null,
                'type' => $type,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'data' => $data['data'] ?? null,
                'recorded_at' => $data['recorded_at'],
            ]);

            HealthRecordCreated::dispatch($record);

            return $record;
        });
    }
}
