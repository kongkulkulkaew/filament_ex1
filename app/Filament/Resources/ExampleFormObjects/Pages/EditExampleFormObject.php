<?php

namespace App\Filament\Resources\ExampleFormObjects\Pages;

use App\Filament\Resources\ExampleFormObjects\ExampleFormObjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExampleFormObject extends EditRecord
{
    protected static string $resource = ExampleFormObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
