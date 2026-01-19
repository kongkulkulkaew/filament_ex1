<?php

namespace App\Filament\Resources\ExampleFormObjects;

use App\Filament\Resources\ExampleFormObjects\Pages\CreateExampleFormObject;
use App\Filament\Resources\ExampleFormObjects\Pages\EditExampleFormObject;
use App\Filament\Resources\ExampleFormObjects\Pages\ListExampleFormObjects;
use App\Filament\Resources\ExampleFormObjects\Schemas\ExampleFormObjectForm;
use App\Filament\Resources\ExampleFormObjects\Tables\ExampleFormObjectsTable;
use App\Models\ExampleFormObject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExampleFormObjectResource extends Resource
{
    protected static ?string $model = ExampleFormObject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'ตัวอย่าง Form Object';

    protected static ?string $modelLabel = 'ตัวอย่าง Form Object';

    protected static ?string $pluralModelLabel = 'ตัวอย่าง Form Objects';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return ExampleFormObjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExampleFormObjectsTable::configure($table);
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
            'index' => ListExampleFormObjects::route('/'),
            'create' => CreateExampleFormObject::route('/create'),
            'edit' => EditExampleFormObject::route('/{record}/edit'),
        ];
    }
}
