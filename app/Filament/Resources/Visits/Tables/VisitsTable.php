<?php

declare(strict_types=1);

namespace App\Filament\Resources\Visits\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class VisitsTable
{
    public static function configure(
        Table $table,
    ): Table {
        return $table
            ->columns([
                TextColumn::make('patient.user.name')
                    ->label('بیمار')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('doctor.user.name')
                    ->label('پزشک')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('visited_at')
                    ->label('تاریخ ویزیت')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate(
                            $state,
                        ),
                    )
                    ->sortable(),

                TextColumn::make('appointment_id')
                    ->label('نوبت')
                    ->formatStateUsing(
                        fn (
                            $state,
                        ): string => $state
                            ? 'دارد'
                            : 'بدون نوبت',
                    )
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate(
                            $state,
                        ),
                    )
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true,
                    ),
            ])
            ->filters([
                SelectFilter::make('doctor_id')
                    ->label('پزشک')
                    ->relationship(
                        name: 'doctor',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (
                            $record,
                        ): string => $record
                            ->user?->name
                            ?? "پزشک #{$record->id}",
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('patient_id')
                    ->label('بیمار')
                    ->relationship(
                        name: 'patient',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (
                            $record,
                        ): string => $record
                            ->user?->name
                            ?? "بیمار #{$record->id}",
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('مشاهده')
                    ->modalHeading('جزئیات ویزیت')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن')
                    ->infolist(
                        fn (
                            Schema $schema,
                        ): Schema => $schema
                            ->components([
                                Section::make('اطلاعات ویزیت')
                                    ->schema([
                                        TextEntry::make(
                                            'patient.user.name',
                                        )
                                            ->label('بیمار')
                                            ->weight('bold'),

                                        TextEntry::make(
                                            'doctor.user.name',
                                        )
                                            ->label('پزشک')
                                            ->weight('bold'),

                                        TextEntry::make(
                                            'visited_at',
                                        )
                                            ->label('تاریخ ویزیت')
                                            ->formatStateUsing(
                                                fn (
                                                    $state,
                                                ): string => jalaliDate(
                                                    $state,
                                                ),
                                            ),

                                        TextEntry::make(
                                            'appointment_id',
                                        )
                                            ->label('نوبت')
                                            ->formatStateUsing(
                                                fn (
                                                    $state,
                                                ): string => $state
                                                    ? 'مرتبط با نوبت'
                                                    : 'بدون نوبت',
                                            )
                                            ->badge(),
                                    ])
                                    ->columns(2),

                                Section::make('یادداشت پزشک')
                                    ->schema([
                                        TextEntry::make(
                                            'doctor_notes',
                                        )
                                            ->hiddenLabel()
                                            ->placeholder(
                                                'یادداشتی ثبت نشده است.',
                                            )
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('تشخیص')
                                    ->schema([
                                        TextEntry::make('diagnosis')
                                            ->hiddenLabel()
                                            ->placeholder(
                                                'تشخیصی ثبت نشده است.',
                                            )
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('برنامه درمانی')
                                    ->schema([
                                        TextEntry::make(
                                            'treatment_plan',
                                        )
                                            ->hiddenLabel()
                                            ->placeholder(
                                                'برنامه درمانی ثبت نشده است.',
                                            )
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('پیگیری')
                                    ->schema([
                                        TextEntry::make('follow_up')
                                            ->hiddenLabel()
                                            ->placeholder(
                                                'اطلاعاتی برای پیگیری ثبت نشده است.',
                                            )
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('اطلاعات ثبت')
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('تاریخ ثبت')
                                            ->formatStateUsing(
                                                fn (
                                                    $state,
                                                ): string => jalaliDate(
                                                    $state,
                                                ),
                                            ),

                                        TextEntry::make('updated_at')
                                            ->label('آخرین ویرایش')
                                            ->formatStateUsing(
                                                fn (
                                                    $state,
                                                ): string => jalaliDate(
                                                    $state,
                                                ),
                                            ),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->collapsed(),
                            ]),
                    ),

                EditAction::make()
                    ->label('ویرایش'),

                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->defaultSort(
                'visited_at',
                'desc',
            );
    }
}
