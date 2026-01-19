<?php

namespace App\Filament\Resources\TimeLogs\Tables;

use App\Models\TimeLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimeLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_date')
                    ->label('วันที่')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('start_time')
                    ->label('เวลาเริ่ม')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('end_time')
                    ->label('เวลาสิ้นสุด')
                    ->time('H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('ระยะเวลา')
                    ->state(function (TimeLog $record): ?string {
                        if ($record->start_time && $record->end_time) {
                            $start = \Carbon\Carbon::parse($record->start_time);
                            $end = \Carbon\Carbon::parse($record->end_time);
                            $diff = $start->diff($end);

                            return sprintf('%d ชั่วโมง %d นาที', $diff->h, $diff->i);
                        }

                        return '-';
                    }),

                TextColumn::make('notes')
                    ->label('หมายเหตุ')
                    ->limit(50)
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('สร้างเมื่อ')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('log_date', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
