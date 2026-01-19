<?php

namespace App\Filament\Resources\ExampleFormObjects\Pages;

use App\Filament\Resources\ExampleFormObjects\ExampleFormObjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExampleFormObjects extends ListRecords
{
    protected static string $resource = ExampleFormObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
