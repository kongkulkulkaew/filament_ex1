<?php

namespace App\Filament\Resources\ConditionalExamples\Pages;

use App\Filament\Resources\ConditionalExamples\ConditionalExampleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConditionalExamples extends ListRecords
{
    protected static string $resource = ConditionalExampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
