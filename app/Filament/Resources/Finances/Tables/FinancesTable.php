<?php

namespace App\Filament\Resources\Finances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class FinancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->sortable()->searchable(),
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('category')->label('الفئة')->sortable()->searchable(),
                TextColumn::make('type')->label('النوع')->sortable()->searchable(),
                TextColumn::make('amount')->label('المبلغ')->sortable()->searchable(),
                TextColumn::make('user.name')->label('المستخدم')->sortable()->searchable(),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime()->sortable(),
            ])
            ->filters([
                
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
