<?php

namespace App\Filament\Resources\ConditionalExamples;

use App\Filament\Resources\ConditionalExamples\Pages\CreateConditionalExample;
use App\Filament\Resources\ConditionalExamples\Pages\EditConditionalExample;
use App\Filament\Resources\ConditionalExamples\Pages\ListConditionalExamples;
use App\Filament\Resources\ConditionalExamples\Schemas\ConditionalExampleForm;
use App\Filament\Resources\ConditionalExamples\Tables\ConditionalExamplesTable;
use App\Models\ConditionalExample;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConditionalExampleResource extends Resource
{
    protected static ?string $model = ConditionalExample::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ConditionalExampleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConditionalExamplesTable::configure($table);
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
            'index' => ListConditionalExamples::route('/'),
            'create' => CreateConditionalExample::route('/create'),
            'edit' => EditConditionalExample::route('/{record}/edit'),
        ];
    }
}
