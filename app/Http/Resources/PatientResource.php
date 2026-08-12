<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'medical_record_number' => $this->medical_record_number,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender' => $this->gender?->value,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'address' => $this->address,
            'notes' => $this->notes,

            'user' => new UserResource(
                $this->whenLoaded('user')
            ),

            'doctors' => DoctorResource::collection(
                $this->whenLoaded('doctors')
            ),

            'caregivers' => CaregiverResource::collection(
                $this->whenLoaded('caregivers')
            ),
        ];
    }
}
