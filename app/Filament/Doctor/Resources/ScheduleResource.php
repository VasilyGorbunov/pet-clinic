<?php

namespace App\Filament\Doctor\Resources;

use App\Enums\AppointmentsStatus;
use App\Filament\Doctor\Resources\ScheduleResource\Pages;
use App\Filament\Doctor\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\DatePicker::make('date')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\Repeater::make('slots')
                        ->relationship()
                        ->schema([
                            Forms\Components\TimePicker::make('start')
                                ->native(false)
                                ->seconds(false)
                                ->required(),
                            Forms\Components\TimePicker::make('end')
                                ->native(false)
                                ->seconds(false)
                                ->required(),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(Tables\Grouping\Group::make('clinic.name')->collapsible()->titlePrefixedWithLabel(false))
            ->groupingSettingsInDropdownOnDesktop()
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slots')
                    ->badge()
                    ->formatStateUsing(fn (Slot $state) => $state->start->format('H:i') . ' - ' . $state->end->format('H:i')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(fn (Schedule $record) => $record->slots()->delete()),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
