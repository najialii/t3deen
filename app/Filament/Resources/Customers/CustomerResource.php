<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Traits\ScopedToRefinery;   
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
// Add these missing imports:
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

    use Filament\Actions\EditAction;
    use Filament\Actions\ViewAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class CustomerResource extends Resource
{
    
    
    
    use ScopedToRefinery;
    protected static ?string $model = Customer::class;

     public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return $user?->isRefineryAdmin() || $user?->isSalesManager();
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user?->isRefineryAdmin() || $user?->isSalesManager();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }
    public static function getNavigationLabel(): string { return 'العملاء'; }
    public static function getModelLabel(): string { return 'عميل'; }
    public static function getPluralModelLabel(): string { return 'العملاء'; }
  

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone_num')
                    ->label('رقم الهاتف')
                    ->searchable(),

                TextColumn::make('refinery.name')
                    ->label('المصفاة')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('refinery')
                    ->label('المصفاة')
                    ->relationship('refinery', 'name'),
            ])
                  ->actions([ViewAction::make(), EditAction::make()])

                                    ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);

    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}