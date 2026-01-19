<?php

namespace App\Filament\Resources\TimeLogs\Pages;

use App\Filament\Resources\TimeLogs\TimeLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeLog extends CreateRecord
{
    protected static string $resource = TimeLogResource::class;
}
