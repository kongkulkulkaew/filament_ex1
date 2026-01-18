<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * CustomersTable - Table Schema สำหรับ Customer
 * 
 * หลักการ:
 * - ใช้ Filament Table Components
 * - เพิ่ม custom actions และ filters
 * - เพิ่ม custom bulk actions
 */
class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Custom query: eager load orders เพื่อลด N+1 queries
                return $query->withCount('orders');
            })
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('ชื่อ-นามสกุล')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable() // คัดลอกได้
                    ->tooltip('คลิกเพื่อคัดลอก'),

                TextColumn::make('email')
                    ->label('อีเมล')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->url(fn ($record) => "mailto:{$record->email}"), // คลิกเพื่อส่งอีเมล

                TextColumn::make('phone')
                    ->label('เบอร์โทร')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->url(fn ($record) => "tel:{$record->phone}"), // คลิกเพื่อโทร

                TextColumn::make('address')
                    ->label('ที่อยู่')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->address) // แสดงที่อยู่เต็มเมื่อ hover
                    ->wrap(),

                TextColumn::make('orders_count')
                    ->label('จำนวนออเดอร์')
                    ->counts('orders')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state) => $state . ' ออเดอร์'),

                IconColumn::make('has_orders')
                    ->label('มีออเดอร์')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => $record->orders()->exists()),

                TextColumn::make('created_at')
                    ->label('วันที่สร้าง')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('วันที่อัปเดต')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter: ค้นหาด้วยชื่อหรืออีเมล
                Filter::make('search')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('ค้นหาชื่อ'),
                        \Filament\Forms\Components\TextInput::make('email')
                            ->label('ค้นหาอีเมล'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['name'],
                                fn (Builder $query, $name): Builder => $query->where('name', 'like', "%{$name}%")
                            )
                            ->when(
                                $data['email'],
                                fn (Builder $query, $email): Builder => $query->where('email', 'like', "%{$email}%")
                            );
                    }),

                // Filter: มีออเดอร์หรือไม่
                Filter::make('has_orders')
                    ->label('มีออเดอร์')
                    ->form([
                        \Filament\Forms\Components\Select::make('has_orders')
                            ->label('มีออเดอร์')
                            ->options([
                                'yes' => 'มีออเดอร์',
                                'no' => 'ไม่มีออเดอร์',
                            ])
                            ->placeholder('ทั้งหมด')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $state = $data['has_orders'] ?? null;
                        
                        if (empty($state)) {
                            return $query;
                        }
                        
                        return match ($state) {
                            'yes' => $query->has('orders'),
                            'no' => $query->doesntHave('orders'),
                            default => $query,
                        };
                    }),

                // Filter: วันที่สร้าง
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
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->recordActions([
                // Custom Action: แก้ไข
                EditAction::make()
                    ->label('แก้ไข')
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),

                // Custom Action: ลบ (พร้อม confirmation)
                DeleteAction::make()
                    ->label('ลบ')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('ยืนยันการลบ')
                    ->modalDescription('คุณแน่ใจหรือไม่ว่าต้องการลบลุกค้านี้? การลบจะไม่สามารถยกเลิกได้')
                    ->modalSubmitActionLabel('ลบ')
                    ->modalCancelActionLabel('ยกเลิก')
                    ->action(function ($record) {
                        // Custom logic: ตรวจสอบว่ามีออเดอร์หรือไม่
                        if ($record->orders()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->title('ไม่สามารถลบได้')
                                ->body('ลูกค้านี้มีออเดอร์อยู่ ไม่สามารถลบได้')
                                ->danger()
                                ->send();
                            return;
                        }

                        // ลบลูกค้า
                        $record->delete();

                        \Filament\Notifications\Notification::make()
                            ->title('ลบสำเร็จ')
                            ->body('ลบลุกค้าเรียบร้อยแล้ว')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Custom Bulk Action: ลบหลายรายการ
                    DeleteBulkAction::make()
                        ->label('ลบที่เลือก')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            // Custom logic: ตรวจสอบว่ามีออเดอร์หรือไม่
                            $hasOrders = $records->filter(fn ($record) => $record->orders()->exists());
                            
                            if ($hasOrders->isNotEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('ไม่สามารถลบได้')
                                    ->body('มีลูกค้าบางรายที่มีออเดอร์อยู่ ไม่สามารถลบได้')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // ลบลูกค้าที่เลือก
                            $records->each->delete();

                            \Filament\Notifications\Notification::make()
                                ->title('ลบสำเร็จ')
                                ->body('ลบลุกค้า ' . $records->count() . ' รายการเรียบร้อยแล้ว')
                                ->success()
                                ->send();
                        }),

                    // Custom Bulk Action: ส่งอีเมล
                    \Filament\Actions\Action::make('send_email')
                        ->label('ส่งอีเมล')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            // Custom logic: ส่งอีเมลให้ลูกค้าที่เลือก
                            $emails = $records->pluck('email')->toArray();
                            
                            \Filament\Notifications\Notification::make()
                                ->title('ส่งอีเมล')
                                ->body('ส่งอีเมลให้ลูกค้า ' . count($emails) . ' รายการ')
                                ->success()
                                ->send();
                            
                            // ในโปรเจคจริงควรส่งอีเมลจริงๆ
                            // Mail::to($emails)->send(new CustomerNotification());
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('ยังไม่มีลูกค้า')
            ->emptyStateDescription('เริ่มต้นโดยการสร้างลูกค้าใหม่')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
