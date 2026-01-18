<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('ลูกค้า')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('ชื่อ')
                            ->required(),
                        TextInput::make('email')
                            ->label('อีเมล')
                            ->email(),
                        TextInput::make('phone')
                            ->label('เบอร์โทร'),
                        Textarea::make('address')
                            ->label('ที่อยู่'),
                    ])
                    ->columnSpanFull(),
                
                TextInput::make('order_number')
                    ->label('เลขที่ออเดอร์')
                    ->disabled()
                    ->dehydrated()
                    ->default(fn () => 'ORD-' . str_pad((string) (\App\Models\Order::max('id') + 1), 6, '0', STR_PAD_LEFT)),
                
                Select::make('status')
                    ->label('สถานะ')
                    ->options(OrderStatus::class)
                    ->default(OrderStatus::Pending)
                    ->required()
                    ->native(false),
                
                Textarea::make('shipping_address')
                    ->label('ที่อยู่จัดส่ง')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                
                Textarea::make('notes')
                    ->label('หมายเหตุ')
                    ->rows(2)
                    ->columnSpanFull(),
                
                Repeater::make('orderItems')
                    ->label('รายการสินค้า')
                    ->relationship('orderItems')
                    ->table([
                        TableColumn::make('สินค้า')
                            ->markAsRequired(),
                        TableColumn::make('จำนวน')
                            ->markAsRequired(),
                        TableColumn::make('ราคาต่อหน่วย')
                            ->markAsRequired(),
                        TableColumn::make('รวม'),
                    ])
                    ->schema([
                        Select::make('product_id')
                            ->label('สินค้า')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('unit_price', $product->price);
                                        // Calculate line total
                                        $quantity = $get('quantity') ?? 1;
                                        $set('line_total', $quantity * $product->price);
                                    }
                                }
                            }),
                        
                        TextInput::make('quantity')
                            ->label('จำนวน')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $unitPrice = $get('unit_price') ?? 0;
                                $set('line_total', $state * $unitPrice);
                            }),
                        
                        TextInput::make('unit_price')
                            ->label('ราคาต่อหน่วย')
                            ->numeric()
                            ->required()
                            ->prefix('฿')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $quantity = $get('quantity') ?? 1;
                                $set('line_total', $quantity * $state);
                            }),
                        
                        TextInput::make('line_total')
                            ->label('รวม')
                            ->numeric()
                            ->prefix('฿')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->defaultItems(1)
                    ->minItems(1)
                    ->reorderable(false)
                    ->addActionLabel('เพิ่มรายการสินค้า')
                    ->columnSpanFull(),
                
                TextInput::make('subtotal')
                    ->label('ยอดรวม')
                    ->numeric()
                    ->prefix('฿')
                    ->disabled()
                    ->dehydrated()
                    ->default(0),
                
                TextInput::make('total')
                    ->label('ยอดรวมทั้งหมด')
                    ->numeric()
                    ->prefix('฿')
                    ->disabled()
                    ->dehydrated()
                    ->default(0),
            ]);
    }
}
