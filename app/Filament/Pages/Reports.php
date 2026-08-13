<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Alert;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Pages\Page;

final class Reports extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'گزارش‌ها';

    protected static ?string $title = 'گزارش‌های سلامت';

    protected static string|null|\UnitEnum $navigationGroup = 'گزارش‌ها';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.reports';

    public function getPatientCount(): int
    {
        return Patient::query()->count();
    }

    public function getAlertCount(): int
    {
        return Alert::query()->count();
    }

    public function getOpenAlertCount(): int
    {
        return Alert::query()
            ->where('status', 'open')
            ->count();
    }

    public function getAppointmentCount(): int
    {
        return Appointment::query()->count();
    }
}
