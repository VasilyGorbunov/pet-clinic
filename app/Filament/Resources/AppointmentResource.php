<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentsStatus;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Role;
use App\Models\Slot;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        $doctorRole = Role::whereName('doctor')->first();

        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('pet_id')
                        ->required()
                        ->relationship('pet', 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\DatePicker::make('date')
                        ->native(false)
                        ->required()
                        ->live(),
                    Forms\Components\Select::make('doctor_id')
                        ->native(false)
                        ->options(function (Forms\Get $get) use ($doctorRole) {
                            return Filament::getTenant()
                                ->users()
                                ->whereBelongsTo($doctorRole)
                                ->whereHas('schedules', function (Builder $query) use ($get) {
                                    $query->where('date', $get('date'));
                                })
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->hidden(fn(Forms\Get $get) => blank($get('date'))),
                    Forms\Components\Select::make('slot_id')
                        ->native(false)
                        ->relationship('slot', 'start')
                        ->getOptionLabelFromRecordUsing(fn(Slot $record) => $record->start->format('H::i')),
                    Forms\Components\TextInput::make('description')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->native(false)
                        ->options(AppointmentsStatus::class)
                        ->visibleOn(Pages\EditAppointment::class),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pet.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start')
                    ->time()
                    ->label('From')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->time()
                    ->label('From')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Confirm')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentsStatus::Confirmed;
                        $record->save();
                    })
                    ->visible(fn(Appointment $record) => $record->status == AppointmentsStatus::Created)
                    ->color('success')
                    ->icon('heroicon-o-check'),
                Tables\Actions\Action::make('Cancel')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentsStatus::Canceled;
                        $record->save();
                    })
                    ->visible(fn(Appointment $record) => $record->status != AppointmentsStatus::Canceled)
                    ->color('danger')
                    ->icon('heroicon-o-x-mark'),
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
