<?php

declare(strict_types=1);

namespace App\Services\HealthMonitoring;

use App\Enums\VitalSignType;
use App\Events\VitalSignRecorded;
use App\Models\VitalSign;
use Illuminate\Support\Facades\DB;

final class VitalSignService
{
    /**
     * @param array{
     *     patient_id:int,
     *     recorded_by?:int|null,
     *     type:VitalSignType|string,
     *     value:float,
     *     secondary_value?:float|null,
     *     unit?:string|null,
     *     recorded_at:\DateTimeInterface,
     *     source?:string,
     *     notes?:string|null
     * } $data
     */
    public function record(array $data): VitalSign
    {
        return DB::transaction(function () use ($data): VitalSign {
            $type = $data['type'] instanceof VitalSignType
                ? $data['type']
                : VitalSignType::from($data['type']);

            $vitalSign = VitalSign::query()->create([
                'patient_id' => $data['patient_id'],
                'recorded_by' => $data['recorded_by'] ?? null,
                'type' => $type,
                'value' => $data['value'],
                'secondary_value' => $data['secondary_value'] ?? null,
                'unit' => $data['unit'] ?? $type->unit(),
                'recorded_at' => $data['recorded_at'],
                'source' => $data['source'] ?? 'manual',
                'notes' => $data['notes'] ?? null,
            ]);

            VitalSignRecorded::dispatch($vitalSign);

            return $vitalSign;
        });
    }
}
