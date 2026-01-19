<?php

namespace App\Filament\Resources\ConditionalExamples\Pages;

use App\Filament\Resources\ConditionalExamples\ConditionalExampleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConditionalExample extends EditRecord
{
    protected static string $resource = ConditionalExampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
