<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentsStatus;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 1;

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
                    Forms\Components\Select::make('clinic_id')
                        ->relationship('clinic', 'name')
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function(Forms\Set $set) {
                            $set('date', null);
                            $set('doctor', null);
                        }),
                    Forms\Components\DatePicker::make('date')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('doctor', null)),
                    Forms\Components\Select::make('doctor')
                        ->native(false)
                        ->options(function (Forms\Get $get) use ($doctorRole) {
                            /** @phpstan-ignore-next-line  */
                            return User::whereBelongsTo($doctorRole)
                                ->whereHas('schedules', function (Builder $query) use ($get) {
                                    $dayOfWeek = Carbon::parse($get('date'))->dayOfWeek;
                                    $query->where('day_of_week', $dayOfWeek)
                                        ->where('clinic_id', $get('clinic_id'));
                                })
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->hidden(fn (Forms\Get $get) => blank($get('date')))
                        ->live()
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('slot_id', null)),
                    Forms\Components\Select::make('slot_id')
                        ->native(false)
                        ->required()
                        ->relationship(
                            name: 'slot',
                            titleAttribute: 'start',
                            modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                                $doctor = User::find($get('doctor'));
                                $dayOfWeek = Carbon::parse($get('date'))->dayOfWeek;
                                $query->whereHas('schedule', function (Builder $query) use ($doctor, $dayOfWeek, $get) {
                                    $query
                                        ->where('clinic_id', $get('clinic_id'))
                                        ->where('day_of_week', $dayOfWeek)
                                        ->whereBelongsTo($doctor, 'owner');
                                });
                            })
                        ->hidden(fn (Forms\Get $get) => blank($get('doctor')))
                        ->getOptionLabelFromRecordUsing(fn (Slot $record) => $record->formatted_time),
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
                Tables\Columns\TextColumn::make('slot.schedule.owner.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clinic.name')
                    ->label('Clinic')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.schedule.date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.formatted_time')
                    ->label('Time')
                    ->badge()
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
                    ->visible(fn (Appointment $record) => $record->status == AppointmentsStatus::Created)
                    ->color('success')
                    ->icon('heroicon-o-check'),
                Tables\Actions\Action::make('Cancel')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentsStatus::Canceled;
                        $record->save();
                    })
                    ->visible(fn (Appointment $record) => $record->status != AppointmentsStatus::Canceled)
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
