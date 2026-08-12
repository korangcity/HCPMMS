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
            'user_id' => $this->user_id,

            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullName(),

            'national_code' => $this->national_code,
            'birth_date' => $this->birth_date?->toDateString(),
            'gender' => $this->gender?->value,
            'gender_label' => $this->gender?->label(),

            'phone' => $this->phone,
            'address' => $this->address,

            'emergency_contact' => [
                'name' => $this->emergency_contact_name,
                'phone' => $this->emergency_contact_phone,
                'relation' => $this->emergency_contact_relation,
            ],

            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),

            'chronic_diseases' => ChronicDiseaseResource::collection(
                $this->whenLoaded('chronicDiseases')
            ),

            'health_records' => HealthRecordResource::collection(
                $this->whenLoaded('healthRecords')
            ),

            'doctors' => UserResource::collection(
                $this->whenLoaded('doctors')
            ),

            'caregivers' => UserResource::collection(
                $this->whenLoaded('caregivers')
            ),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
