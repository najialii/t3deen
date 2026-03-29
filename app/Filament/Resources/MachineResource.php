<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineResource\Pages;
use App\Filament\Traits\ScopedToRefinery;
use App\Models\Machine;
use App\Models\Refinery;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MachineResource extends Resource
{
    use ScopedToRefinery;

    protected static ?string $model = Machine::class;

    // Only Refinery Admins and Sales Managers see Machines
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

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-cog-6-tooth'; }
    public static function getNavigationSort(): ?int { return 1; }
    public static function getNavigationGroup(): string|\UnitEnum|null { return 'العمليات'; }
    public static function getNavigationLabel(): string { return 'الآلات'; }
    public static function getModelLabel(): string { return 'آلة'; }
    public static function getPluralModelLabel(): string { return 'الآلات'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('اسم الآلة')->required()->maxLength(255),
            Select::make('unit')
                ->label('وحدة القياس')
                ->options(array_combine(Machine::$units, Machine::$units))
                ->required(),
            TextInput::make('price_per_unit')
                ->label('السعر لكل وحدة')
                ->numeric()
                ->required()
                ->prefix('SDG'),
            Select::make('refinery_id')
                ->label('المصفاة')
                ->options(function () {
                    $user = Auth::user();
                    if ($user->isSystemAdmin()) {
                        return Refinery::pluck('name', 'id');
                    }
                    return Refinery::where('id', $user->refinery_id)->pluck('name', 'id');
                })
                ->searchable()
                ->required(),
            Toggle::make('is_active')->label('نشط')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('unit')->label('الوحدة')->badge(),
                TextColumn::make('price_per_unit')->label('السعر/وحدة')->money('SDG')->sortable(),
                TextColumn::make('refinery.name')->label('المصفاة')->searchable(),
                IconColumn::make('is_active')->label('نشط')->boolean(),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('refinery')->label('المصفاة')->relationship('refinery', 'name'),
                SelectFilter::make('unit')->label('الوحدة')->options(array_combine(Machine::$units, Machine::$units)),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMachines::route('/'),
            'create' => Pages\CreateMachine::route('/create'),
            'edit'   => Pages\EditMachine::route('/{record}/edit'),
        ];
    }
}
