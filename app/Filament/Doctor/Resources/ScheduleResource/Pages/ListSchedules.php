<?php

namespace App\Filament\Doctor\Resources\ScheduleResource\Pages;

use App\Enums\DaysOfTheWeek;
use App\Filament\Doctor\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Sunday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Sunday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Sunday)->count()),
            'Monday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Monday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Monday)->count()),
            'Tuesday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Tuesday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Tuesday)->count()),
            'Wednesday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Wednesday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Wednesday)->count()),
            'Thursday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Thursday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Thursday)->count()),
            'Friday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Friday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Friday)->count()),
            'Saturday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Saturday))
                ->badge(Schedule::query()->where('day_of_week', DaysOfTheWeek::Saturday)->count()),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return Carbon::today()->format('l');
    }
}
