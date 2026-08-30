<x-filament-panels::page>

    <div class="space-y-6">

        {{-- فیلترها --}}
        <x-filament::section
            collapsible
            heading="فیلترهای گزارش"
        >

            {{ $this->form }}

            <div class="mt-6 flex flex-wrap gap-3">

                <x-filament::button
                    wire:click="applyFilters"
                    icon="heroicon-o-funnel"
                >
                    اعمال فیلترها
                </x-filament::button>

                <x-filament::button
                    wire:click="resetFilters"
                    color="gray"
                    icon="heroicon-o-arrow-path"
                >
                    پاک کردن فیلترها
                </x-filament::button>

            </div>

        </x-filament::section>


        {{-- آمار --}}
        <div>
            @livewire(
                \App\Filament\Pages\Reports\Widgets\ReportStatsWidget::class,
                ['filters' => $filters],
                key('report-stats-' . md5(json_encode($filters)))
            )
        </div>


        {{-- نمودار فشار خون --}}
        <div>
            @livewire(
                \App\Filament\Pages\Reports\Widgets\BloodPressureChart::class,
                ['filters' => $filters],
                key('blood-pressure-' . md5(json_encode($filters)))
            )
        </div>


        {{-- نمودار قند خون --}}
        <div>
            @livewire(
                \App\Filament\Pages\Reports\Widgets\BloodGlucoseChart::class,
                ['filters' => $filters],
                key('blood-glucose-' . md5(json_encode($filters)))
            )
        </div>


        {{-- نمودار وزن --}}
        <div>
            @livewire(
                \App\Filament\Pages\Reports\Widgets\WeightChart::class,
                ['filters' => $filters],
                key('weight-' . md5(json_encode($filters)))
            )
        </div>


        {{-- سایر علائم حیاتی --}}
        <div>
            @livewire(
                \App\Filament\Pages\Reports\Widgets\VitalSignsChart::class,
                ['filters' => $filters],
                key('vital-signs-' . md5(json_encode($filters)))
            )
        </div>

    </div>

</x-filament-panels::page>
