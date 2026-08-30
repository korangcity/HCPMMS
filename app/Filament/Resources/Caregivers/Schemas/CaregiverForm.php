<?php

declare(strict_types=1);

namespace App\Filament\Resources\Caregivers\Schemas;

use App\Enums\CaregiverStatus;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class CaregiverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات حساب کاربری')
                ->schema([
                    Select::make('user_id')
                        ->label('کاربر مراقب')
                        ->relationship(
                            name: 'user',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query
                                ->role('caregiver')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn (User $record): string => $record->name
                        )
                        ->getOptionLabelUsing(
                            fn ($value): string => User::query()
                                ->whereKey($value)
                                ->value('name') ?? '-'
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->unique(
                            table: 'caregivers',
                            column: 'user_id',
                            ignoreRecord: true,
                        ),
                ]),

            Section::make('اطلاعات مراقب')
                ->schema([

                    Select::make('relationship')
                        ->label('نسبت با بیمار')
                        ->options([
                            'پدر' => 'پدر',
                            'مادر' => 'مادر',
                            'همسر' => 'همسر',
                            'فرزند' => 'فرزند',
                            'خواهر' => 'خواهر',
                            'برادر' => 'برادر',
                            'سایر' => 'سایر',
                        ])
                        ->searchable(),

                    Select::make('status')
                        ->label('وضعیت')
                        ->options(
                            collect(CaregiverStatus::cases())
                                ->mapWithKeys(
                                    fn (CaregiverStatus $status): array => [
                                        $status->value => $status->label(),
                                    ]
                                )
                                ->all()
                        )
                        ->default(
                            CaregiverStatus::ACTIVE->value
                        )
                        ->required(),
                ])
                ->columns(2),

            Section::make('بیماران تحت مراقبت')
                ->schema([
                    Select::make('patients')
                        ->label('بیماران')
                        ->relationship(
                            name: 'patients',
                            titleAttribute: 'id'
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn (Patient $record): string =>
                                $record->user?->name ?? '-'
                        )
                        ->searchable()
                        ->preload()
                        ->multiple(),
                ]),

            Section::make('اطلاعات تکمیلی')
                ->schema([
                    Textarea::make('address')
                        ->label('آدرس')
                        ->rows(3)
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->label('توضیحات')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
