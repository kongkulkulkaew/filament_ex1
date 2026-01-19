<?php

namespace App\Filament\Resources\TimeLogs\Pages;

use App\Filament\Resources\TimeLogs\TimeLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTimeLog extends EditRecord
{
    protected static string $resource = TimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
