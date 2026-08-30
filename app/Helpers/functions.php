<?php

use Carbon\Carbon;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;


function sanitize($data): string|array
{
    /**
     * @param string $value
     *
     * @return string
     */
    $sanitizeValue = static fn(string $value): string => htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    if (is_null($data)) {
        return '';
    }
    return is_array($data) ? array_map($sanitizeValue, $data) : $sanitizeValue($data);
}

function normalize_search_query(string $q): string
{
    return trim(
        preg_replace('/\s+/u', ' ', mb_strtolower($q))
    );
}


function parseToCarbon($date): ?Carbon
{
    if (!$date) {
        return null;
    }

    if ($date instanceof Carbon || $date instanceof \DateTime) {
        return $date instanceof Carbon ? $date : Carbon::instance($date);
    }

    if (is_string($date)) {
        if (preg_match('/^\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}$/', $date)) {
            try {
                return Jalalian::fromFormat('Y/m/d H:i', $date)->toCarbon();
            } catch (\Exception $e) {
            }
        }

        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date)) {
            try {
                return Jalalian::fromFormat('Y/m/d', $date)->toCarbon();
            } catch (\Exception $e) {

            }
        }

        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    return null;
}

function isValidCivilizationCode($code)
{
    if (!preg_match('/^\d{10}$/', $code)) {
        return false;
    }

    $digits = str_split($code);
    $checkDigit = (int)$digits[9];
    $sum = 0;

    for ($i = 0; $i < 9; $i++) {
        $sum += (int)$digits[$i] * (10 - $i);
    }

    $remainder = $sum % 11;
    $calculatedCheckDigit = ($remainder < 2) ? $remainder : (11 - $remainder);

    return $checkDigit === $calculatedCheckDigit;
}

function shortText($text, $limit = 100)
{
    return mb_strlen($text, 'UTF-8') > $limit
        ? mb_substr($text, 0, $limit, 'UTF-8') . '...'
        : $text;
}

function checkIfMobileIsCorrect($mobile): bool
{
    if (substr($mobile, 0, 2) == "09" and strlen($mobile) == 11 and ctype_digit($mobile))
        return true;
    return false;
}

//function jalaliDate(mixed $date, string $format = 'Y/m/d H:i', string $default = '---'): string
//{
//    if (blank($date)) {
//        return $default;
//    }
//
//    if (! $date instanceof Carbon) {
//        $date = Carbon::parse($date);
//    }
//
//    return CalendarUtils::strftime($format, $date);
//}

function roleLabel(string $role): string
{
    return match ($role) {
        'admin' => 'ادمین',
        'doctor' => 'پزشک',
        'caregiver' => 'مراقب بیمار',
        'patient' => 'بیمار',
    };
}

function toPersianNumbers(string $value): string
{
    return strtr($value, [
        '0' => '۰',
        '1' => '۱',
        '2' => '۲',
        '3' => '۳',
        '4' => '۴',
        '5' => '۵',
        '6' => '۶',
        '7' => '۷',
        '8' => '۸',
        '9' => '۹',
    ]);
}

function jalaliDate(
    mixed  $date,
    string $format = 'Y/m/d H:i',
    string $default = '---'
): string
{
    if (blank($date)) {
        return $default;
    }

    if (!$date instanceof Carbon) {
        $date = Carbon::parse($date);
    }

    return toPersianNumbers(
        CalendarUtils::strftime($format, $date)
    );
}


