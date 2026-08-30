<?php

declare(strict_types=1);

namespace App\Filament\Pages\Reports;

use App\Exports\HealthRecordsExport;
use App\Filament\Forms\Components\PersianDateTimePicker;
use App\Models\Appointment;
use App\Models\ChronicDisease;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Visit;
use App\Services\ReportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

final class Reports extends Page
{
    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel =
        'گزارش‌ها';

    protected static ?string $title =
        'گزارش‌های سلامت';

    protected static ?string $slug =
        'reports';

    protected static string|null|\UnitEnum $navigationGroup =
        'گزارش‌ها';

    protected string $view =
        'filament.pages.reports.reports';

    public ?array $filters = [
        'date_from' => null,
        'date_to' => null,
        'patient_id' => null,
        'doctor_id' => null,
        'chronic_disease_id' => null,
        'appointment_id' => null,
        'visit_id' => null,
    ];

    public function form(
        Schema $schema,
    ): Schema {
        return $schema
            ->components([
                PersianDateTimePicker::make('date_from')
                    ->autocomplete('off')
                    ->label('از تاریخ'),

                PersianDateTimePicker::make('date_to')
                    ->autocomplete('off')
                    ->label('تا تاریخ')
                    ->afterOrEqual('date_from'),

                Select::make('patient_id')
                    ->label('بیمار')
                    ->options(
                        Patient::query()
                            ->with('user')
                            ->get()
                            ->mapWithKeys(
                                fn (
                                    Patient $patient,
                                ): array => [
                                    $patient->id =>
                                        $patient->user?->name
                                        ?? "بیمار #{$patient->id}",
                                ],
                            )
                            ->all(),
                    )
                    ->searchable()
                    ->preload()
                    ->live(),

                Select::make('doctor_id')
                    ->label('پزشک')
                    ->options(
                        Doctor::query()
                            ->with('user')
                            ->get()
                            ->mapWithKeys(
                                fn (
                                    Doctor $doctor,
                                ): array => [
                                    $doctor->id =>
                                        $doctor->user?->name
                                        ?? "پزشک #{$doctor->id}",
                                ],
                            )
                            ->all(),
                    )
                    ->searchable()
                    ->preload()
                    ->live(),

                Select::make('chronic_disease_id')
                    ->label('بیماری مزمن')
                    ->options(
                        ChronicDisease::query()
                            ->orderBy('name')
                            ->pluck(
                                'name',
                                'id',
                            )
                            ->all(),
                    )
                    ->searchable()
                    ->preload()
                    ->live(),

                Select::make('appointment_id')
                    ->label('نوبت')
                    ->options(
                        Appointment::query()
                            ->with('patient.user')
                            ->latest()
                            ->get()
                            ->mapWithKeys(
                                fn (
                                    Appointment $appointment,
                                ): array => [
                                    $appointment->id =>
                                        (
                                            $appointment
                                                ->patient
                                                ?->user
                                                ?->name
                                            ?? "بیمار #{$appointment->patient_id}"
                                        )
                                        . ' - '
                                        . (
                                            $appointment
                                                ->scheduled_at
                                                ?->format(
                                                    'Y/m/d H:i',
                                                )
                                            ?? 'بدون تاریخ'
                                        ),
                                ],
                            )
                            ->all(),
                    )
                    ->searchable()
                    ->preload()
                    ->live(),

                Select::make('visit_id')
                    ->label('ویزیت')
                    ->options(
                        Visit::query()
                            ->with([
                                'patient.user',
                                'doctor.user',
                            ])
                            ->latest()
                            ->get()
                            ->mapWithKeys(
                                fn (
                                    Visit $visit,
                                ): array => [
                                    $visit->id =>
                                        (
                                            $visit
                                                ->patient
                                                ?->user
                                                ?->name
                                            ?? "بیمار #{$visit->patient_id}"
                                        )
                                        . ' - '
                                        . (
                                            $visit
                                                ->doctor
                                                ?->user
                                                ?->name
                                            ?? "پزشک #{$visit->doctor_id}"
                                        )
                                        . ' - '
                                        . (
                                            $visit
                                                ->visited_at
                                                ?->format(
                                                    'Y/m/d H:i',
                                                )
                                            ?? 'بدون تاریخ'
                                        ),
                                ],
                            )
                            ->all(),
                    )
                    ->searchable()
                    ->preload()
                    ->live(),
            ])
            ->columns(3)
            ->statePath('filters');
    }

    public function applyFilters(): void
    {
        $this->filters = $this->normalizeFilters(
            $this->filters,
        );

        $this->dispatch(
            'reports-filters-updated',
            filters: $this->filters,
        );
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'date_from' => null,
            'date_to' => null,
            'patient_id' => null,
            'doctor_id' => null,
            'chronic_disease_id' => null,
            'appointment_id' => null,
            'visit_id' => null,
        ];

        $this->form->fill($this->filters);

        $this->dispatch(
            'reports-filters-updated',
            filters: $this->filters,
        );
    }

    public function exportExcel()
    {
        return Excel::download(
            new HealthRecordsExport(
                $this->getNormalizedFilters(),
            ),
            'health-report.xlsx',
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('خروجی Excel')
                ->icon(
                    Heroicon::OutlinedArrowDownTray,
                )
                ->color('success')
                ->action(
                    fn () => $this->exportExcel(),
                ),
        ];
    }

    public function getReportService(): ReportService
    {
        return app(ReportService::class);
    }

    /**
     * تبدیل تاریخ‌های جلالی فرم به تاریخ میلادی
     * برای استفاده در Query دیتابیس.
     */
    private function normalizeFilters(
        array $filters,
    ): array {
        $filters['date_from'] = $this->convertJalaliToGregorian(
            $filters['date_from'] ?? null,
        );

        $filters['date_to'] = $this->convertJalaliToGregorian(
            $filters['date_to'] ?? null,
        );

        return $filters;
    }

    /**
     * تبدیل تاریخ جلالی به میلادی.
     *
     * ورودی:
     * 1405/06/08
     * 1405/06/08 12:30
     *
     * خروجی:
     * 2026-08-30 00:00:00
     * 2026-08-30 12:30:00
     */
    private function convertJalaliToGregorian(
        mixed $value,
    ): ?string {
        if (blank($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        /*
         * اگر به هر دلیل مقدار از قبل میلادی باشد،
         * دوباره تبدیلش نمی‌کنیم.
         */
        if (preg_match(
            '/^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}(?::\d{2})?)?$/',
            $value,
        )) {
            return strlen($value) === 10
                ? $value . ' 00:00:00'
                : $value . (strlen($value) === 16 ? ':00' : '');
        }

        /*
         * تاریخ جلالی:
         *
         * 1405/06/08
         * 1405/06/08 12:30
         */
        if (! preg_match(
            '/^(\d{4})\/(\d{2})\/(\d{2})(?:\s+(\d{2}):(\d{2}))?$/',
            $value,
            $matches,
        )) {
            return null;
        }

        $year = (int) $matches[1];
        $month = (int) $matches[2];
        $day = (int) $matches[3];

        $hour = isset($matches[4])
            ? (int) $matches[4]
            : 0;

        $minute = isset($matches[5])
            ? (int) $matches[5]
            : 0;

        try {
            $jalalian = Jalalian::fromFormat(
                'Y/m/d H:i',
                sprintf(
                    '%04d/%02d/%02d %02d:%02d',
                    $year,
                    $month,
                    $day,
                    $hour,
                    $minute,
                ),
            );

            return $jalalian
                ->toCarbon()
                ->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * فیلترهای مناسب برای Query و Export.
     */
    public function getNormalizedFilters(): array
    {
        return $this->normalizeFilters(
            $this->filters ?? [],
        );
    }

    /**
     * Query گزارش‌ها.
     */
    public function getReportQuery(): Builder
    {
        return $this->getReportService()->query(
            $this->getNormalizedFilters(),
        );
    }
}
