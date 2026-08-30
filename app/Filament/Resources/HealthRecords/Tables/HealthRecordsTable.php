<?php

declare(strict_types=1);

namespace App\Filament\Resources\HealthRecords\Tables;

use App\Enums\Medication;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class HealthRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.user.name')
                    ->label('بیمار')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('chronicDisease.name')
                    ->label('بیماری مزمن')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('recorder.name')
                    ->label('ثبت‌کننده')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate($state),
                    )
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true,
                    ),
            ])
            ->filters([
                SelectFilter::make('chronic_disease_id')
                    ->label('بیماری مزمن')
                    ->relationship(
                        name: 'chronicDisease',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('مشاهده')
                    ->modalHeading('جزئیات پرونده سلامت')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن')
                    ->infolist(
                        fn (Schema $schema): Schema => $schema
                            ->components([
                                Section::make('اطلاعات اصلی')
                                    ->description('اطلاعات پایه پرونده سلامت')
                                    ->schema([
                                        TextEntry::make('patient.user.name')
                                            ->label('بیمار')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->color('primary'),

                                        TextEntry::make('chronicDisease.name')
                                            ->label('بیماری مزمن')
                                            ->placeholder('ندارد')
                                            ->weight('bold'),

                                        TextEntry::make('recorder.name')
                                            ->label('ثبت‌کننده')
                                            ->weight('bold'),

                                        TextEntry::make('description')
                                            ->label('توضیحات')
                                            ->placeholder('توضیحی ثبت نشده است')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(3)
                                    ->collapsible(),

                                Section::make('اندازه‌گیری‌ها')
                                    ->description('اندازه‌گیری‌های ثبت‌شده برای این پرونده')
                                    ->schema([
                                        RepeatableEntry::make('measurement_information')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextEntry::make('type')
                                                    ->label('نوع اندازه‌گیری')
                                                    ->formatStateUsing(
                                                        fn (?string $state): string => match ($state) {
                                                            'blood_pressure' => 'فشار خون',
                                                            'blood_glucose' => 'قند خون',
                                                            'weight' => 'وزن',
                                                            'temperature' => 'دمای بدن',
                                                            'heart_rate' => 'ضربان قلب',
                                                            'oxygen_saturation' => 'اشباع اکسیژن',
                                                            default => 'نامشخص',
                                                        },
                                                    )
                                                    ->badge()
                                                    ->color('primary'),

                                                TextEntry::make('value')
                                                    ->label('مقدار')
                                                    ->weight('bold')
                                                    ->size('lg'),

                                                TextEntry::make('unit')
                                                    ->label('واحد')
                                                    ->badge()
                                                    ->color('gray'),

                                                TextEntry::make('recorded_at')
                                                    ->label('زمان اندازه‌گیری')
                                                    ->formatStateUsing(
                                                        fn ($state): string => filled($state)
                                                            ? jalaliDate($state)
                                                            : '—',
                                                    ),
                                            ])
                                            ->columns(4)
                                            ->contained(true),
                                    ])
                                    ->collapsible(),

                                Section::make('داروهای تجویز شده')
                                    ->description('داروهای تجویز شده در این پرونده')
                                    ->schema([
                                        RepeatableEntry::make('medication_information')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextEntry::make('medication')
                                                    ->label('دارو')
                                                    ->formatStateUsing(
                                                        fn (?string $state): string => Medication::tryFrom(
                                                            $state ?? '',
                                                        )?->label() ?? 'نامشخص',
                                                    )
                                                    ->badge()
                                                    ->color('success')
                                                    ->weight('bold'),

                                                TextEntry::make('dosage')
                                                    ->label('دوز')
                                                    ->placeholder('—')
                                                    ->weight('bold'),

                                                TextEntry::make('administration_method')
                                                    ->label('نحوه مصرف')
                                                    ->placeholder('—'),

                                                TextEntry::make('frequency')
                                                    ->label('تعداد / دفعات')
                                                    ->placeholder('—'),

                                                TextEntry::make('start_date')
                                                    ->label('تاریخ شروع')
                                                    ->formatStateUsing(
                                                        fn ($state): string => filled($state)
                                                            ? jalaliDate($state)
                                                            : '—',
                                                    ),

                                                TextEntry::make('end_date')
                                                    ->label('تاریخ پایان')
                                                    ->formatStateUsing(
                                                        fn ($state): string => filled($state)
                                                            ? jalaliDate($state)
                                                            : 'بدون تاریخ پایان',
                                                    ),
                                            ])
                                            ->columns(3)
                                            ->contained(true),
                                    ])
                                    ->visible(
                                        fn ($record): bool => ! empty(
                                        $record->medication_information
                                        ),
                                    )
                                    ->collapsible(),

                                Section::make('اطلاعات ثبت')
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('تاریخ ثبت')
                                            ->formatStateUsing(
                                                fn ($state): string => filled($state)
                                                    ? jalaliDate($state)
                                                    : '—',
                                            ),

                                        TextEntry::make('updated_at')
                                            ->label('آخرین ویرایش')
                                            ->formatStateUsing(
                                                fn ($state): string => filled($state)
                                                    ? jalaliDate($state)
                                                    : '—',
                                            ),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->collapsed(),
                            ]),
                    ),

                EditAction::make()
                    ->label('ویرایش'),
            ])
            ->defaultSort(
                'created_at',
                'desc',
            );
    }
}
