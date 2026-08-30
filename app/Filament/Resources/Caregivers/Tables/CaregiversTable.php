<?php

declare(strict_types=1);

namespace App\Filament\Resources\Caregivers\Tables;

use App\Enums\CaregiverStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class CaregiversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn ($query) => $query->with([
                    'user',
                    'patients.user',
                ])
            )
            ->columns([

                TextColumn::make('user.name')
                    ->label('نام مراقب')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.civilization_code')
                    ->label('کد ملی')
                    ->searchable(),

                TextColumn::make('user.mobile')
                    ->label('موبایل')
                    ->searchable(),

                TextColumn::make('relationship')
                    ->label('نسبت')
                    ->searchable(),

                TextColumn::make('patients')
                    ->label('بیماران')
                    ->state(
                        fn ($record): string => $record->patients
                            ->map(
                                fn ($patient): string =>
                                    $patient->user?->name ?? '-'
                            )
                            ->join('، ')
                    )
                    ->wrap(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            CaregiverStatus $state
                        ): string => $state->label()
                    )
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate($state),
                    )
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(
                        collect(CaregiverStatus::cases())
                            ->mapWithKeys(
                                fn (
                                    CaregiverStatus $status
                                ): array => [
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
