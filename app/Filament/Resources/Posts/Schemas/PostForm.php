<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('หัวข้อ')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                    
                RichEditor::make('content')
                    ->label('เนื้อหา')
                    ->required()
                    ->columnSpanFull(),
                    
                Toggle::make('is_published')
                    ->label('เผยแพร่')
                    ->default(false),
            ]);
    }
}