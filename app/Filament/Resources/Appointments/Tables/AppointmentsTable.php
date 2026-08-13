<?php

declare(strict_types=1);

namespace App\Filament\Resources\Appointments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class AppointmentsTable
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

                TextColumn::make('doctor.first_name')
                    ->label('پزشک')
                    ->formatStateUsing(
                        fn ($record): string =>
                        "{$record->doctor?->first_name} {$record->doctor?->last_name}"
                    )
                    ->searchable(),

                TextColumn::make('scheduled_at')
                    ->label('زمان')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('نوع'),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'no_show' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'scheduled' => 'برنامه‌ریزی شده',
                        'completed' => 'انجام شده',
                        'cancelled' => 'لغو شده',
                        'no_show' => 'عدم مراجعه',
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
            ->defaultSort('scheduled_at', 'desc');
    }
}
