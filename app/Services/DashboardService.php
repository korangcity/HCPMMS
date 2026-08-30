<?php

declare(strict_types=1);

namespace App\Services;

//use App\Models\Alert;
use App\Models\Appointment;
use App\Models\ChronicDisease;
use App\Models\Doctor;
use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class DashboardService
{
    public function patientsCount(): int
    {
        return Patient::query()->count();
    }

    public function doctorsCount(): int
    {
        return Doctor::query()->count();
    }

    public function healthRecordsCount(): int
    {
        return HealthRecord::query()->count();
    }

    public function appointmentsCount(): int
    {
        return Appointment::query()->count();
    }

    public function visitsCount(): int
    {
        return Visit::query()->count();
    }

    public function diseasesCount(): int
    {
        return ChronicDisease::query()->count();
    }

//    public function alertsCount(): int
//    {
//        return Alert::query()->count();
//    }

    public function measurementsCount(): int
    {
        return HealthRecord::query()
            ->get(['measurement_information'])
            ->sum(
                fn (HealthRecord $record): int => count(
                    $record->measurement_information ?? [],
                ),
            );
    }

    /**
     * آمار اندازه‌گیری‌ها در 30 روز اخیر.
     */
    public function measurementTrend(
        string $type,
        int $days = 30,
    ): array {
        $from = now()
            ->subDays($days - 1)
            ->startOfDay();

        $records = HealthRecord::query()
            ->where('created_at', '>=', $from)
            ->orderBy('created_at')
            ->get([
                'measurement_information',
                'created_at',
            ]);

        $data = [];

        foreach ($records as $record) {
            foreach (
                $record->measurement_information ?? []
                as $measurement
            ) {
                if (($measurement['type'] ?? null) !== $type) {
                    continue;
                }

                $value = $this->numericValue(
                    $measurement['value'] ?? null,
                );

                if ($value === null) {
                    continue;
                }

                $date = $this->measurementDate(
                    $measurement['recorded_at'] ?? null,
                    $record->created_at,
                );

                if ($date === null) {
                    continue;
                }

                $key = $date->format('Y-m-d');

                $data[$key][] = $value;
            }
        }

        ksort($data);

        $labels = [];
        $values = [];

        foreach ($data as $date => $items) {
            $labels[] = JalaliDate($date);
            $values[] = round(
                array_sum($items) / count($items),
                2,
            );
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->measurementLabel($type),
                    'data' => $values,
                    'fill' => false,
                ],
            ],
        ];
    }

    public function bloodPressureTrend(
        int $days = 30,
    ): array {
        $from = now()
            ->subDays($days - 1)
            ->startOfDay();

        $records = HealthRecord::query()
            ->where('created_at', '>=', $from)
            ->orderBy('created_at')
            ->get([
                'measurement_information',
                'created_at',
            ]);

        $data = [];

        foreach ($records as $record) {
            foreach (
                $record->measurement_information ?? []
                as $measurement
            ) {
                if (
                    ($measurement['type'] ?? null)
                    !== 'blood_pressure'
                ) {
                    continue;
                }

                $value = trim(
                    (string) (
                        $measurement['value'] ?? ''
                    ),
                );

                if (
                    ! preg_match(
                        '/^(\d+(?:\.\d+)?)\s*\/\s*(\d+(?:\.\d+)?)$/',
                        $value,
                        $matches,
                    )
                ) {
                    continue;
                }

                $date = $this->measurementDate(
                    $measurement['recorded_at'] ?? null,
                    $record->created_at,
                );

                if ($date === null) {
                    continue;
                }

                $key = $date->format('Y-m-d');

                $data[$key]['systolic'][] =
                    (float) $matches[1];

                $data[$key]['diastolic'][] =
                    (float) $matches[2];
            }
        }

        ksort($data);

        $labels = [];
        $systolic = [];
        $diastolic = [];

        foreach ($data as $date => $items) {
            $labels[] = jalaliDate($date);

            $systolic[] = round(
                array_sum($items['systolic'])
                / count($items['systolic']),
                2,
            );

            $diastolic[] = round(
                array_sum($items['diastolic'])
                / count($items['diastolic']),
                2,
            );
        }

        return [
            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'سیستولیک',
                    'data' => $systolic,
                ],
                [
                    'label' => 'دیاستولیک',
                    'data' => $diastolic,
                ],
            ],
        ];
    }

    /**
     * تعداد انواع اندازه‌گیری‌ها در 30 روز اخیر.
     */
    public function measurementTypes(
        int $days = 30,
    ): array {
        $from = now()
            ->subDays($days - 1)
            ->startOfDay();

        $records = HealthRecord::query()
            ->where('created_at', '>=', $from)
            ->get(['measurement_information']);

        $result = [
            'blood_pressure' => 0,
            'blood_glucose' => 0,
            'weight' => 0,
            'temperature' => 0,
            'heart_rate' => 0,
            'oxygen_saturation' => 0,
        ];

        foreach ($records as $record) {
            foreach (
                $record->measurement_information ?? []
                as $measurement
            ) {
                $type = $measurement['type'] ?? null;

                if (isset($result[$type])) {
                    $result[$type]++;
                }
            }
        }

        return [
            'labels' => [
                'فشار خون',
                'قند خون',
                'وزن',
                'دمای بدن',
                'ضربان قلب',
                'اشباع اکسیژن',
            ],
            'datasets' => [
                [
                    'data' => array_values($result),
                ],
            ],
        ];
    }

    private function numericValue(
        mixed $value,
    ): ?float {
        if (! is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    private function measurementDate(
        mixed $value,
        mixed $fallback,
    ): ?Carbon {
        if (blank($value)) {
            return $fallback instanceof Carbon
                ? $fallback
                : Carbon::parse($fallback);
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function measurementLabel(
        string $type,
    ): string {
        return match ($type) {
            'blood_pressure' => 'فشار خون',
            'blood_glucose' => 'قند خون',
            'weight' => 'وزن',
            'temperature' => 'دمای بدن',
            'heart_rate' => 'ضربان قلب',
            'oxygen_saturation' => 'اشباع اکسیژن',
            default => 'اندازه‌گیری',
        };
    }
}
