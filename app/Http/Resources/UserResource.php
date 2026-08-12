<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status->value,
            'roles' => $this->whenLoaded(
                'roles',
                fn (): array => $this->roles
                    ->pluck('name')
                    ->values()
                    ->all()
            ),
            'patient' => new PatientResource(
                $this->whenLoaded('patient')
            ),
            'doctor' => new DoctorResource(
                $this->whenLoaded('doctor')
            ),
            'caregiver' => new CaregiverResource(
                $this->whenLoaded('caregiver')
            ),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
