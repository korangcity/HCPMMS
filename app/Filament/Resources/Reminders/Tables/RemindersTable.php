<?php

declare(strict_types=1);

namespace App\Filament\Resources\Reminders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class RemindersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.first_name')
                    ->label('بیمار')
                    ->formatStateUsing(
                        fn ($record): string =>
                        "{$record->patient?->first_name} {$record->patient?->last_name}"
                    )
                    ->searchable(),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge(),

                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable(),

                TextColumn::make('remind_at')
                    ->label('زمان')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('نوع')
                    ->options([
                        'medication' => 'دارو',
                        'appointment' => 'ویزیت',
                        'test' => 'آزمایش',
                    ]),

                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار',
                        'completed' => 'انجام شده',
                        'cancelled' => 'لغو شده',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('remind_at');
    }
}
