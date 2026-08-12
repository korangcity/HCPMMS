<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CaregiverResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type?->value,
            'national_identifier' => $this->national_identifier,
            'address' => $this->address,
            'notes' => $this->notes,

            'user' => new UserResource(
                $this->whenLoaded('user')
            ),

            'patients' => PatientResource::collection(
                $this->whenLoaded('patients')
            ),
        ];
    }
}
