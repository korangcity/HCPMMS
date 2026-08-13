<?php

declare(strict_types=1);

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات پزشک')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('first_name')
                            ->label('نام')
                            ->required(),

                        TextInput::make('last_name')
                            ->label('نام خانوادگی')
                            ->required(),

                        TextInput::make('medical_code')
                            ->label('شماره نظام پزشکی')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('specialization')
                            ->label('تخصص')
                            ->required(),

                        TextInput::make('phone')
                            ->label('شماره تماس')
                            ->tel(),

                        TextInput::make('email')
                            ->label('ایمیل')
                            ->email(),
                    ]),
                ]),

            Section::make('اطلاعات تکمیلی')
                ->schema([
                    Textarea::make('bio')
                        ->label('معرفی')
                        ->rows(5)
                        ->columnSpanFull(),

                    Select::make('status')
                        ->label('وضعیت')
                        ->options([
                            'active' => 'فعال',
                            'inactive' => 'غیرفعال',
                        ])
                        ->default('active')
                        ->required(),
                ]),
        ]);
    }
}
