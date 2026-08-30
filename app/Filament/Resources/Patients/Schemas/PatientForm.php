<?php

declare(strict_types=1);

namespace App\Filament\Resources\Patients\Schemas;

use App\Enums\PatientStatus;
//use App\Models\Caregiver;
use App\Filament\Forms\Components\PersianDateTimePicker;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات حساب کاربری')
                ->schema([
                    Select::make('user_id')
                        ->label('کاربر بیمار')
                        ->relationship(
                            name: 'user',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query
                                ->role('patient')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn (User $record): string => $record->name
                        )
                        ->getOptionLabelUsing(
                            fn ($value): string => User::query()
                                ->where('id', $value)
                                ->value('name') ?? '-'
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->unique(
                            table: 'patients',
                            column: 'user_id',
                            ignoreRecord: true,
                        ),
                ]),

            Section::make('اطلاعات بیمار')
                ->schema([

                    PersianDateTimePicker::make('birth_date')
                        ->label('تاریخ تولد')
                    ->autocomplete('off')
                        ,

                    Select::make('blood_type')
                        ->label('گروه خونی')
                        ->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ])
                        ->searchable(),

                    Select::make('status')
                        ->label('وضعیت')
                        ->options(
                            collect(PatientStatus::cases())
                                ->mapWithKeys(
                                    fn (PatientStatus $status): array => [
                                        $status->value => $status->label(),
                                    ]
                                )
                                ->all()
                        )
                        ->default(PatientStatus::ACTIVE->value)
                        ->required(),
                ])
                ->columns(2),

            Section::make('پزشکان')
                ->schema([
                    Select::make('doctors')
                        ->label('پزشکان')
                        ->relationship(
                            name: 'doctors',
                            titleAttribute: 'user_id'
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn (Doctor $record): string =>
                                $record->user?->name ?? '-'
                        )
                        ->searchable()
                        ->preload()
                        ->multiple(),
                ]),

//            Section::make('مراقبین')
//                ->schema([
//                    Select::make('caregivers')
//                        ->label('مراقبین')
//                        ->relationship(
//                            name: 'caregivers',
//                            titleAttribute: 'user_id'
//                        )
//                        ->getOptionLabelFromRecordUsing(
//                            fn (Caregiver $record): string =>
//                                $record->user?->name ?? '-'
//                        )
//                        ->searchable()
//                        ->preload()
//                        ->multiple(),
//                ]),

            Section::make('تماس اضطراری')
                ->schema([
                    TextInput::make('emergency_contact_name')
                        ->label('نام فرد تماس اضطراری')
                        ->maxLength(255),

                    TextInput::make('emergency_contact_phone')
                        ->label('شماره تماس اضطراری')
                        ->tel()
                        ->maxLength(30),
                ])
                ->columns(2),

            Section::make('سایر اطلاعات')
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
