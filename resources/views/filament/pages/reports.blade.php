<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-filament::section>
            <div class="text-sm text-gray-500">
                تعداد بیماران
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getPatientCount() }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500">
                کل هشدارها
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getAlertCount() }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500">
                هشدارهای باز
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getOpenAlertCount() }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500">
                کل ویزیت‌ها
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getAppointmentCount() }}
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
