<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'medical_license_number' => $this->medical_license_number,
            'specialty' => $this->specialty,
            'bio' => $this->bio,
            'is_available' => $this->is_available,

            'user' => new UserResource(
                $this->whenLoaded('user')
            ),

            'patients' => PatientResource::collection(
                $this->whenLoaded('patients')
            ),
        ];
    }
}
