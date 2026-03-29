<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Refinery;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-users'; }
    public static function getNavigationSort(): ?int { return 2; }
    public static function getNavigationGroup(): string|\UnitEnum|null { return 'الإدارة'; }
    public static function getNavigationLabel(): string { return 'المستخدمون'; }
    public static function getModelLabel(): string { return 'مستخدم'; }
    public static function getPluralModelLabel(): string { return 'المستخدمون'; }

    // Sales managers cannot access user management at all
    public static function shouldRegisterNavigation(): bool
    {
        return ! (Auth::user()?->isSalesManager() ?? true);
    }

    public static function canAccess(): bool
    {
        return ! (Auth::user()?->isSalesManager() ?? true);
    }

    // System admin sees all; refinery admin sees only their refinery's users
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->isSystemAdmin()) {
            return $query;
        }

        // Refinery admin: see only sales managers in their refinery
        return $query->where('refinery_id', $user->refinery_id)
            ->where('role', 'sales_manager');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('الاسم')->required()->maxLength(255),
            TextInput::make('email')->label('البريد الإلكتروني')->email()->required()->unique(ignoreRecord: true),
            TextInput::make('phone_number')->label('رقم الهاتف')->tel()->maxLength(20),
            TextInput::make('address')->label('العنوان')->maxLength(255),
            Select::make('role')
                ->label('الدور')
                ->options(function () {
                    $user = Auth::user();
                    if ($user->isSystemAdmin()) {
                        return ['system_admin' => 'مدير النظام', 'refinery_admin' => 'مدير المصفاة', 'sales_manager' => 'مدير المبيعات'];
                    }
                    return ['sales_manager' => 'مدير المبيعات'];
                })
                ->required()
                ->live(),
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
                ->nullable(),
            TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->required(fn ($record) => $record === null)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
                TextColumn::make('phone_number')->label('الهاتف'),
                TextColumn::make('role')->label('الدور')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'system_admin'   => 'danger',
                        'refinery_admin' => 'warning',
                        'sales_manager'  => 'success',
                        default          => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'system_admin'   => 'مدير النظام',
                        'refinery_admin' => 'مدير المصفاة',
                        'sales_manager'  => 'مدير المبيعات',
                        default          => $state,
                    }),
                TextColumn::make('refinery.name')->label('المصفاة')->default('—'),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')->label('الدور')->options([
                    'system_admin'   => 'مدير النظام',
                    'refinery_admin' => 'مدير المصفاة',
                    'sales_manager'  => 'مدير المبيعات',
                ]),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
