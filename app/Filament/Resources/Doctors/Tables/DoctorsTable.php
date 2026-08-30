<?php

declare(strict_types=1);

namespace App\Filament\Resources\Doctors\Tables;

use App\Enums\AcademicDegree;
use App\Enums\DoctorSpecialty;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('نام پزشک')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('medical_license_number')
                    ->label('شماره نظام پزشکی')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('specialty')
                    ->label('تخصص')
                    ->badge()
                    ->formatStateUsing(
                        fn (DoctorSpecialty $state): string => $state->label(),
                    )
                    ->sortable(),

                TextColumn::make('academic_degree')
                    ->label('مدرک')
                    ->badge()
                    ->formatStateUsing(
                        fn (AcademicDegree $state): string => $state->label(),
                    )
                    ->sortable(),

                TextColumn::make('years_of_experience')
                    ->label('سابقه')
                    ->numeric()
                    ->suffix(' سال')
                    ->sortable(),

                TextColumn::make('patients_count')
                    ->label('تعداد بیماران')
                    ->counts('patients')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate($state),
                    )
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('specialty')
                    ->label('تخصص')
                    ->options(
                        collect(DoctorSpecialty::cases())
                            ->mapWithKeys(
                                fn (DoctorSpecialty $specialty): array => [
                                    $specialty->value => $specialty->label(),
                                ],
                            )
                            ->all()
                    ),

                SelectFilter::make('academic_degree')
                    ->label('مدرک تحصیلی')
                    ->options(
                        collect(AcademicDegree::cases())
                            ->mapWithKeys(
                                fn (AcademicDegree $degree): array => [
                                    $degree->value => $degree->label(),
                                ],
                            )
                            ->all()
                    ),
            ])

            ->recordActions([
                ViewAction::make()
                    ->label('نمایش')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(
                        fn ($record): string => 'اطلاعات پزشک: ' . $record->user->name,
                    )
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن')
                    ->schema([
                        Section::make('اطلاعات کاربر')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('نام پزشک'),

                                        TextEntry::make('user.email')
                                            ->label('ایمیل')
                                            ->placeholder('ثبت نشده'),

                                        TextEntry::make('user.phone')
                                            ->label('شماره تماس')
                                            ->placeholder('ثبت نشده'),
                                    ]),
                            ]),

                        Section::make('اطلاعات حرفه‌ای')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('medical_license_number')
                                            ->label('شماره نظام پزشکی'),

                                        TextEntry::make('specialty')
                                            ->label('تخصص')
                                            ->badge()
                                            ->formatStateUsing(
                                                fn (DoctorSpecialty $state): string => $state->label(),
                                            ),

                                        TextEntry::make('academic_degree')
                                            ->label('مدرک تحصیلی')
                                            ->badge()
                                            ->formatStateUsing(
                                                fn (AcademicDegree $state): string => $state->label(),
                                            ),

                                        TextEntry::make('years_of_experience')
                                            ->label('سابقه کاری')
                                            ->suffix(' سال'),
                                    ]),
                            ]),

                        Section::make('معرفی')
                            ->schema([
                                TextEntry::make('bio')
                                    ->hiddenLabel()
                                    ->placeholder('توضیحی ثبت نشده')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('اطلاعات سیستم')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('تاریخ ثبت')
                                            ->formatStateUsing(
                                                fn ($state): string => jalaliDate($state),
                                            ),

                                        TextEntry::make('updated_at')
                                            ->label('آخرین ویرایش')
                                            ->formatStateUsing(
                                                fn ($state): string => jalaliDate($state),
                                            ),

                                        TextEntry::make('patients_count')
                                            ->label('تعداد بیماران')
                                            ->state(
                                                fn ($record): int => $record->patients()->count(),
                                            ),
                                    ]),
                            ]),
                    ]),

                EditAction::make()
                    ->label('ویرایش')
                    ->icon('heroicon-o-pencil'),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
