<?php

namespace App\Filament\Resources\Payrolls;

use App\Filament\Resources\Payrolls\Pages\CreatePayroll;
use App\Filament\Resources\Payrolls\Pages\EditPayroll;
use App\Filament\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\Traits\ScopedToRefinery;
use App\Filament\Resources\Payrolls\Pages\ViewPayroll;
use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Filament\Resources\Payrolls\Schemas\PayrollInfolist;
use App\Filament\Resources\Payrolls\Tables\PayrollsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\payroll;
use BackedEnum;

class PayrollResource extends Resource
{
    use ScopedToRefinery; 

    protected static ?string $model = payroll::class;
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

    protected static ?string $recordTitleAttribute = 'payroll';


    public static function getNavigationGroup(): string|\UnitEnum|null 
{ 
   return 'المالية'; 
}

public static function getNavigationLabel(): string 
{ 
    return 'مسيرات الرواتب'; 
}

public static function getModelLabel(): string 
{ 
    return 'راتب'; 
}

public static function getPluralModelLabel(): string 
{ 
    return 'الرواتب'; 
} 

    public static function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayrollInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
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
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'view' => ViewPayroll::route('/{record}'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }
}
