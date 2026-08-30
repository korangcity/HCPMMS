<?php

namespace App\Filament\Forms\Components;

use Carbon\Carbon;
use Filament\Forms\Components\TextInput;

class PersianDateTimePicker extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (PersianDateTimePicker $component, $state) {
            if (!$state) {
                return;
            }

            if (is_string($state)) {
                $state = trim($state);

                $state = preg_replace('/^\/+/', '', $state);

                if (preg_match('/\/\d{4}\s+\d{4}\//', $state) || preg_match('/^\d{4}\s+\d{4}\//', $state)) {
                    preg_match_all('/(\d{4}\/\d{2}\/\d{2})(?:\s+(\d{2}:\d{2}))?/', $state, $allMatches, PREG_SET_ORDER);

                    if (!empty($allMatches)) {
                        $lastMatch = end($allMatches);
                        $datePart = $lastMatch[1];
                        $timePart = $lastMatch[2] ?? '00:00';
                        $state = $datePart . ' ' . $timePart;
                    } else {
                        $component->state('');
                        return;
                    }
                }

                $state = preg_replace('/\s+/', ' ', $state);

                if (preg_match('/^(\d{2}:\d{2})\s+(\d{4}\/\d{2}\/\d{2})$/', $state, $reverseMatches)) {
                    $state = $reverseMatches[2] . ' ' . $reverseMatches[1];
                }

                if (preg_match('/^\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}$/', $state)) {
                    $component->state($state);
                    return;
                }

                if (preg_match('/^\/?\d{4}\/\d{2}\/\d{2}/', $state)) {
                    preg_match_all('/(\d{4}\/\d{2}\/\d{2})(?:\s+(\d{2}:\d{2}))?/', $state, $matches, PREG_SET_ORDER);

                    if (!empty($matches)) {
                        $lastMatch = end($matches);
                        $datePart = $lastMatch[1];
                        $timePart = $lastMatch[2] ?? '00:00';
                        $cleanState = $datePart . ' ' . $timePart;
                        $component->state($cleanState);
                        return;
                    }
                }
            }

            $jalaliDate = $this->convertToJalali($state);
            if ($jalaliDate) {
                $component->state($jalaliDate);
            } else {
                $component->state($state);
            }
        });

        $this->dehydrateStateUsing(function ($state) {
            if (!$state) {
                return $state;
            }

            if (is_string($state)) {
                $state = trim($state);

                $state = preg_replace('/^\/+/', '', $state);
                $state = preg_replace('/\s+/', ' ', $state);

                if (preg_match('/\/\d{4}\s+\d{4}\//', $state) || preg_match('/^\d{4}\s+\d{4}\//', $state)) {
                    preg_match_all('/(\d{4}\/\d{2}\/\d{2})(?:\s+(\d{2}:\d{2}))?/', $state, $allMatches, PREG_SET_ORDER);
                    if (!empty($allMatches)) {
                        $lastMatch = end($allMatches);
                        $datePart = $lastMatch[1];
                        $timePart = $lastMatch[2] ?? '00:00';
                        $state = $datePart . ' ' . $timePart;
                    }
                }

                if (preg_match('/^\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}$/', $state)) {
                    return $this->convertToGregorian($state);
                }

                if (preg_match('/^\/?\d{4}\/\d{2}\/\d{2}/', $state)) {
                    preg_match_all('/(\d{4}\/\d{2}\/\d{2})(?:\s+(\d{2}:\d{2}))?/', $state, $matches, PREG_SET_ORDER);
                    if (!empty($matches)) {
                        $lastMatch = end($matches);
                        $datePart = $lastMatch[1];
                        $timePart = $lastMatch[2] ?? '00:00';
                        $cleanState = $datePart . ' ' . $timePart;
                        return $this->convertToGregorian($cleanState);
                    }
                }
            }

            return $state;
        });

        $this->extraInputAttributes([
            'class' => 'persian-datetime-picker',
            'data-persian-picker' => 'true',
        ]);
    }

    protected function convertToJalali($gregorianDate): ?string
    {
        if (!$gregorianDate) {
            return null;
        }

        try {
            $dateTime = null;

            if ($gregorianDate instanceof Carbon) {
                $dateTime = $gregorianDate->toDateTime();
            } elseif (is_numeric($gregorianDate)) {
                $tehranTimezone = new \DateTimeZone('Asia/Tehran');
                $dateTime = new \DateTime('@' . $gregorianDate);
                $dateTime->setTimezone($tehranTimezone);
            } elseif (is_string($gregorianDate)) {
                $tehranTimezone = new \DateTimeZone('Asia/Tehran');
                try {
                    $dateTime = Carbon::parse($gregorianDate)->setTimezone($tehranTimezone)->toDateTime();
                } catch (\Exception $e) {
                    $dateTime = new \DateTime($gregorianDate, $tehranTimezone);
                }
            } else {
                return null;
            }

            if (!$dateTime) {
                return null;
            }

            $jalali = \Morilog\Jalali\Jalalian::fromDateTime($dateTime);
            return $jalali->format('Y/m/d H:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function convertToGregorian($jalaliDate): ?string
    {
        if (!$jalaliDate) {
            return null;
        }

        try {
            $dateTimeParts = explode(' ', $jalaliDate);
            $datePart = $dateTimeParts[0] ?? '';
            $timePart = $dateTimeParts[1] ?? '00:00';

            $dateComponents = explode('/', $datePart);
            if (count($dateComponents) !== 3) {
                return null;
            }

            [$jalaliYear, $jalaliMonth, $jalaliDay] = array_map('intval', $dateComponents);
            $timeComponents = explode(':', $timePart);
            $hour = isset($timeComponents[0]) ? (int) $timeComponents[0] : 0;
            $minute = isset($timeComponents[1]) ? (int) $timeComponents[1] : 0;
            $gregorianDate = \Morilog\Jalali\CalendarUtils::toGregorian($jalaliYear, $jalaliMonth, $jalaliDay);
            [$gregorianYear, $gregorianMonth, $gregorianDay] = $gregorianDate;

            $tehranTimezone = new \DateTimeZone('Asia/Tehran');
            $carbon = Carbon::create(
                $gregorianYear,
                $gregorianMonth,
                $gregorianDay,
                $hour,
                $minute,
                0, // seconds
                $tehranTimezone
            );

            return $carbon->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

}

