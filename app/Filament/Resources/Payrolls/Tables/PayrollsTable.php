<?php

namespace App\Filament\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('payable.name')->label('المستفيد')->sortable()->searchable(),
                TextColumn::make('refinery.name')->label('الخزانة')->sortable()->searchable(),
                TextColumn::make('period_start')->label('بداية الفترة')->date()->sortable(),
                TextColumn::make('period_end')->label('نهاية الفترة')->date()->sortable(),
                // TextColumn::make('payment_amount')->label('مبلغ الدفع')->decimal(places: 2)->sortable(),
                // TextColumn::make('netpay')->label('الراتب الصافي')->decimal(places: 2)->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
