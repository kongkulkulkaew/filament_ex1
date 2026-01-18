<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    // กำหนดไอคอนที่จะแสดงในเมนูนำทางของ Filament dashboard สำหรับ resource นี้
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // กำหนดชื่อของ resource ที่จะแสดงในเมนูนำทาง
    protected static ?string $navigationLabel = 'บทความ';
    
    // กำหนดชื่อที่ใช้แสดงผลสำหรับ model แบบเอกพจน์
    protected static ?string $modelLabel = 'บทความ';
    
    // กำหนดชื่อที่ใช้แสดงผลสำหรับ model แบบพหูพจน์
    protected static ?string $pluralModelLabel = 'บทความทั้งหมด';
    
    // กำหนดกลุ่มเมนู (ถ้าต้องการจัดกลุ่ม)
    protected static UnitEnum|string|null $navigationGroup = 'เนื้อหา';
    
    // กำหนดลำดับการแสดงในเมนู
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}