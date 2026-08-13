<?php

declare(strict_types=1);

namespace App\Filament\Resources\Patients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('last_name')
                    ->label('نام خانوادگی')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('national_code')
                    ->label('کد ملی')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable(),

                TextColumn::make('birth_date')
                    ->label('تاریخ تولد')
                    ->date('Y/m/d')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('ثبت شده')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label('جنسیت')
                    ->options([
                        'male' => 'مرد',
                        'female' => 'زن',
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
