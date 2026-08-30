<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Sex;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile')
                    ->label('موبایل')
                    ->searchable(),

                TextColumn::make('civilization_code')
                    ->label('کد ملی')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('sex')
                    ->label('جنسیت')
                    ->badge()
                    ->formatStateUsing(
                        fn (Sex|string|null $state): string => match (true) {
                            $state instanceof Sex => $state->label(),
                            $state === Sex::Male->value => Sex::Male->label(),
                            $state === Sex::Female->value => Sex::Female->label(),
                            default => 'نامشخص',
                        },
                    ),
                TextColumn::make('age')
                    ->label('سن')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('نقش')
                    ->formatStateUsing(fn ($state) => roleLabel($state))
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->formatStateUsing(fn ($state) => jalaliDate($state))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('نقش')
                    ->options([
                        'admin' => 'مدیر سیستم',
                        'doctor' => 'پزشک',
                        'caregiver' => 'مراقب',
                        'patient' => 'بیمار',
                    ])
                    ->query(
                        fn (
                            Builder $query,
                            array $data,
                        ): Builder => $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, string $role): Builder => $query
                                ->role($role),
                        ),
                    ),

                SelectFilter::make('sex')
                    ->label('جنسیت')
                    ->options([
                        Sex::Male->value => Sex::Male->label(),
                        Sex::Female->value => Sex::Female->label(),
                    ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
