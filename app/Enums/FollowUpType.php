<?php

declare(strict_types=1);

namespace App\Enums;

enum FollowUpType: string
{
    case Appointment = 'appointment';
    case PhoneCall = 'phone_call';
    case Laboratory = 'laboratory';
    case Monitoring = 'monitoring';
    case MedicationReview = 'medication_review';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Appointment => 'ویزیت بعدی',
            self::PhoneCall => 'تماس تلفنی',
            self::Laboratory => 'آزمایش',
            self::Monitoring => 'پایش وضعیت',
            self::MedicationReview => 'بررسی دارو',
            self::Other => 'سایر',
        };
    }
}
