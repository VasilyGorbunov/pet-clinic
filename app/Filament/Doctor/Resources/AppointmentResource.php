<?php

namespace App\Filament\Doctor\Resources;

use App\Enums\AppointmentStatus;
use App\Filament\Doctor\Resources\AppointmentResource\Pages;
use App\Filament\Doctor\Resources\AppointmentResource\RelationManagers\NotesRelationManager;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Slot;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 1;

    public static function getOptionString(Model $record)
    {
        return view('filament.components.select-pet-results', compact('record'))->render();
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('pet_id')
                        ->label("Pet")
                        //->relationship('pet', 'name')
                        ->allowHtml()
                        ->searchable()
                        //->preload()
                        ->required()
                        ->helperText(fn() => Filament::getTenant()->pets->isEmpty()
                            ? new HtmlString('<span class="text-sm text-danger-600">No pets available</span>')
                            : '')
                        ->columnSpanFull()
                        ->getSearchResultsUsing(function (string $search) {
                            $pets = Pet::where('name', 'like', "%{$search}%")->limit(50)->get();
                            return $pets->mapWithKeys(function ($pet) {
                                return [$pet->getKey() => static::getOptionString($pet)];
                            })->toArray();
                        })
                        ->options(function () {
                            $pets = Pet::all();
                            return $pets->mapWithKeys(function ($pet) {
                                return [$pet->getKey() => static::getOptionString($pet)];
                            })->toArray();
                        }),
                    Forms\Components\DatePicker::make('date')
                        ->native(false)
                        ->displayFormat('M d, Y')
                        ->closeOnDateSelection()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn(Set $set) => $set('slot_id', null)),
                    Forms\Components\Select::make('slot_id')
                        ->label('Slot')
                        ->native(false)
                        ->required()
                        ->options(function (Get $get) {
                            $clinic = Filament::getTenant();
                            $doctor = Filament::auth()->user();
                            $dayOfTheWeek = Carbon::parse($get('date'))->dayOfWeek;
                            return Slot::availableFor($doctor, $dayOfTheWeek, $clinic->id)
                                ->get()
                                ->pluck('formatted_time', 'id');
                        })
                        ->hidden(fn(Get $get) => blank($get('date')))
                        ->live(),
                    Forms\Components\TextInput::make('description')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->native(false)
                        ->options(AppointmentStatus::class)
                        ->visibleOn(Pages\EditAppointment::class)
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('pet.avatar')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('pet.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.formatted_time')
                    ->label('Time')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Confirm')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentStatus::Confirmed;
                        $record->save();
                    })
                    ->visible(fn(Appointment $record) => $record->status == AppointmentStatus::Created)
                    ->color('success')
                    ->icon('heroicon-o-check'),
                Tables\Actions\Action::make('Cancel')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentStatus::Canceled;
                        $record->save();
                    })
                    ->visible(fn(Appointment $record) => $record->status != AppointmentStatus::Canceled)
                    ->color('danger')
                    ->icon('heroicon-o-x-mark'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::new()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
