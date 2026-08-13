<?php

declare(strict_types=1);

namespace App\Filament\Resources\Alerts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class AlertsTable
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

                TextColumn::make('severity')
                    ->label('اولویت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('value')
                    ->label('مقدار'),

                TextColumn::make('threshold')
                    ->label('آستانه'),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('زمان')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('severity')
                    ->label('اولویت')
                    ->options([
                        'low' => 'کم',
                        'medium' => 'متوسط',
                        'high' => 'زیاد',
                        'critical' => 'بحرانی',
                    ]),

                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'open' => 'باز',
                        'acknowledged' => 'مشاهده شده',
                        'resolved' => 'برطرف شده',
                    ]),

                SelectFilter::make('type')
                    ->label('نوع')
                    ->options([
                        'blood_pressure' => 'فشار خون',
                        'blood_sugar' => 'قند خون',
                        'heart_rate' => 'ضربان قلب',
                        'oxygen' => 'اکسیژن خون',
                        'weight' => 'وزن',
                        'other' => 'سایر',
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
            ->defaultSort('created_at', 'desc');
    }
}
