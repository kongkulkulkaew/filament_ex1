<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Eager load customer relationship for better performance
                return $query->with('customer');
            })
            ->columns([
                TextColumn::make('order_number')
                    ->label('เลขที่ออเดอร์')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('customer.name')
                    ->label('ลูกค้า')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Search by customer name through relationship
                        return $query->whereHas('customer', function (Builder $q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                
                SelectColumn::make('status')
                    ->label('สถานะ')
                    ->options(OrderStatus::class)
                    ->selectablePlaceholder(false),
                
                TextColumn::make('total')
                    ->label('ยอดรวม')
                    ->money('THB')
                    ->sortable(),
                
                TextColumn::make('orderItems_count')
                    ->label('จำนวนรายการ')
                    ->counts('orderItems')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('วันที่สร้าง')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('สถานะ')
                    ->options(OrderStatus::class)
                    ->multiple(),
                
                Filter::make('created_at')
                    ->label('วันที่สร้าง')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('ตั้งแต่'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('ถึง'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->visible(fn ($record) => auth()->user()?->can('delete', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->visible(fn () => auth()->user()?->can('deleteAny', \App\Models\Order::class)),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
