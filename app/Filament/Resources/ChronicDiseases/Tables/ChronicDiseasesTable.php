<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChronicDiseases\Tables;

use App\Enums\ChronicDiseaseStatus;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ChronicDiseasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('بیماری')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            ChronicDiseaseStatus|string|null $state,
                        ): string => $state instanceof ChronicDiseaseStatus
                            ? $state->label()
                            : (
                                ChronicDiseaseStatus::tryFrom(
                                    (string) $state,
                                )?->label() ?? 'نامشخص'
                            ),
                    ),

                TextColumn::make('health_records_count')
                    ->label('پرونده سلامت')
                    ->counts('healthRecords')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(
                        fn ($state): string => jalaliDate($state),
                    )
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(
                        ChronicDiseaseStatus::options(),
                    ),

            ])
            ->defaultSort(
                'id',
                'desc',
            );
    }
}
