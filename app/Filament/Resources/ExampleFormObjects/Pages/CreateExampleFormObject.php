<?php

namespace App\Filament\Resources\ExampleFormObjects\Pages;

use App\Filament\Resources\ExampleFormObjects\ExampleFormObjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExampleFormObject extends CreateRecord
{
    protected static string $resource = ExampleFormObjectResource::class;
}
