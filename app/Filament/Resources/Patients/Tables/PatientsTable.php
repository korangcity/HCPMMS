<?php

declare(strict_types=1);

namespace App\Filament\Resources\Patients\Tables;

use App\Enums\PatientStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('نام بیمار')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.civilization_code')
                    ->label('کد ملی')
                    ->searchable(),

                TextColumn::make('user.mobile')
                    ->label('موبایل')
                    ->searchable(),

                TextColumn::make('doctors')
                    ->label('پزشکان')
                    ->state(
                        fn ($record): string => $record->doctors
                            ->map(
                                fn ($doctor): string => $doctor->user?->name ?? '-'
                            )
                            ->join(', ')
                    )
                    ->wrap(),

//                TextColumn::make('caregivers.user.name')
//                    ->label('مراقبین')
//                    ->list(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(
                        fn (PatientStatus $state): string =>
                        $state->label()
                    )
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->formatStateUsing(fn ($state) => jalaliDate($state))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(
                        collect(PatientStatus::cases())
                            ->mapWithKeys(
                                fn (PatientStatus $status): array => [
                                    $status->value => $status->label(),
                                ]
                            )
                            ->all()
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(
                'created_at',
                'desc'
            );
    }
}
