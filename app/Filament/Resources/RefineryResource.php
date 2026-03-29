<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefineryResource\Pages;
use App\Filament\Traits\ScopedToRefinery;
use App\Models\Refinery;
use Illuminate\Support\Facades\Auth;
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
use Filament\Tables\Table;

class RefineryResource extends Resource
{
    protected static ?string $model = Refinery::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-building-office-2'; }
    public static function getNavigationSort(): ?int { return 1; }
    public static function getNavigationGroup(): string|\UnitEnum|null { return 'الإدارة'; }
    public static function getNavigationLabel(): string { return 'المصافي'; }
    public static function getModelLabel(): string { return 'مصفاة'; }
    public static function getPluralModelLabel(): string { return 'المصافي'; }

    // Refinery admins only see their own refinery; system admin sees all
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();
        if ($user->isSystemAdmin()) {
            return $query;
        }
        return $query->where('id', $user->refinery_id);
    }

    // Hide from nav for non-system-admins (they access via direct link)
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isSystemAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('الاسم')->required()->maxLength(255),
            TextInput::make('location')->label('الموقع')->maxLength(255),
            TextInput::make('phone')->label('الهاتف')->tel()->maxLength(20),
            Toggle::make('is_active')->label('نشط')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('location')->label('الموقع')->searchable(),
                TextColumn::make('phone')->label('الهاتف'),
                IconColumn::make('is_active')->label('نشط')->boolean(),
                TextColumn::make('machines_count')->counts('machines')->label('الآلات'),
                TextColumn::make('workers_count')->counts('workers')->label('العمال'),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRefineries::route('/'),
            'create' => Pages\CreateRefinery::route('/create'),
            'edit'   => Pages\EditRefinery::route('/{record}/edit'),
        ];
    }
}
