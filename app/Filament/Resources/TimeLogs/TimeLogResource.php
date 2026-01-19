<?php

namespace App\Filament\Resources\TimeLogs;

use App\Filament\Resources\TimeLogs\Pages\CreateTimeLog;
use App\Filament\Resources\TimeLogs\Pages\EditTimeLog;
use App\Filament\Resources\TimeLogs\Pages\ListTimeLogs;
use App\Filament\Resources\TimeLogs\Schemas\TimeLogForm;
use App\Filament\Resources\TimeLogs\Tables\TimeLogsTable;
use App\Models\TimeLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TimeLogResource extends Resource
{
    protected static ?string $model = TimeLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'การลงเวลาเรียน';

    protected static ?string $modelLabel = 'การลงเวลาเรียน';

    protected static ?string $pluralModelLabel = 'การลงเวลาเรียนทั้งหมด';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return TimeLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimeLogsTable::configure($table);
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
            'index' => ListTimeLogs::route('/'),
            'create' => CreateTimeLog::route('/create'),
            'edit' => EditTimeLog::route('/{record}/edit'),
        ];
    }
}
