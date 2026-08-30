<?php

declare(strict_types=1);

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class AppointmentsTable
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

                TextColumn::make('scheduled_at')
                    ->label('تاریخ و ساعت نوبت')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate($state),
                    )
                    ->sortable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            AppointmentStatus|string|null $state,
                        ): string => $state instanceof AppointmentStatus
                            ? $state->label()
                            : AppointmentStatus::tryFrom(
                            (string) $state,
                        )?->label() ?? 'نامشخص',
                    )
                    ->color(
                        fn (
                            AppointmentStatus|string|null $state,
                        ): string => $state instanceof AppointmentStatus
                            ? $state->color()
                            : AppointmentStatus::tryFrom(
                            (string) $state,
                        )?->color() ?? 'gray',
                    )
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
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(
                        collect(AppointmentStatus::cases())
                            ->mapWithKeys(
                                fn (
                                    AppointmentStatus $status,
                                ): array => [
                                    $status->value => $status->label(),
                                ],
                            )
                            ->all(),
                    ),

                SelectFilter::make('doctor_id')
                    ->label('پزشک')
                    ->relationship(
                        name: 'doctor',
                        titleAttribute: 'id',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => $record
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
                        fn ($record): string => $record
                            ->user?->name
                            ?? "بیمار #{$record->id}",
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('مشاهده')
                    ->modalHeading('جزئیات نوبت')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن')
                    ->infolist(
                        fn (
                            Schema $schema,
                        ): Schema => $schema
                            ->components([
                                Section::make('اطلاعات نوبت')
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
                                            'scheduled_at',
                                        )
                                            ->label('تاریخ و ساعت')
                                            ->formatStateUsing(
                                                fn (
                                                    $state,
                                                ): string => jalaliDate(
                                                    $state,
                                                ),
                                            ),

                                        TextEntry::make('status')
                                            ->label('وضعیت')
                                            ->badge()
                                            ->formatStateUsing(
                                                fn (
                                                    AppointmentStatus|string|null $state,
                                                ): string => $state instanceof AppointmentStatus
                                                    ? $state->label()
                                                    : AppointmentStatus::tryFrom(
                                                    (string) $state,
                                                )?->label()
                                                    ?? 'نامشخص',
                                            ),

                                        TextEntry::make('description')
                                            ->label('توضیحات')
                                            ->placeholder('—')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                    ),

                EditAction::make()
                    ->label('ویرایش'),

                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->defaultSort(
                'scheduled_at',
                'desc',
            );
    }
}
