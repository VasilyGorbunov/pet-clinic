<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('reload')
                ->outlined()
                ->icon('heroicon-o-arrow-path')
                ->action(fn() => $this->fillForm()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['doctor'] = $this->record->slot->schedule->owner_id;
        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
