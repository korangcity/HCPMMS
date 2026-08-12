<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class HealthRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'title' => $this->title,
            'description' => $this->description,
            'recorded_at' => $this->recorded_at->toIso8601String(),
            'metadata' => $this->metadata,

            'creator' => new UserResource(
                $this->whenLoaded('creator')
            ),
        ];
    }
}
