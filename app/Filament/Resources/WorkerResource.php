<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkerResource\Pages;
use App\Filament\Traits\ScopedToRefinery;
use App\Models\Refinery;
use App\Models\Worker;
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

class WorkerResource extends Resource
{
    use ScopedToRefinery;

    protected static ?string $model = Worker::class;

    // Only Refinery Admins and Sales Managers see Workers
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

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-user-group'; }
    public static function getNavigationSort(): ?int { return 2; }
    public static function getNavigationGroup(): string|\UnitEnum|null { return 'العمليات'; }
    public static function getNavigationLabel(): string { return 'العمال'; }
    public static function getModelLabel(): string { return 'عامل'; }
    public static function getPluralModelLabel(): string { return 'العمال'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('الاسم')->required()->maxLength(255),
            TextInput::make('phone')->label('الهاتف')->tel()->maxLength(20),
            TextInput::make('national_id')->label('الرقم الوطني')->maxLength(50)->unique(ignoreRecord: true),
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
                TextColumn::make('phone')->label('الهاتف'),
                TextColumn::make('national_id')->label('الرقم الوطني'),
                TextColumn::make('refinery.name')->label('المصفاة')->searchable(),
                IconColumn::make('is_active')->label('نشط')->boolean(),
                TextColumn::make('transactions_count')->counts('transactions')->label('المعاملات'),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('refinery')->label('المصفاة')->relationship('refinery', 'name'),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWorkers::route('/'),
            'create' => Pages\CreateWorker::route('/create'),
            'edit'   => Pages\EditWorker::route('/{record}/edit'),
        ];
    }
}
