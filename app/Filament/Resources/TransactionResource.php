<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Traits\ScopedToRefinery;
use App\Models\Machine;
use App\Models\Refinery;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Worker;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TransactionResource extends Resource
{
    use ScopedToRefinery;

    protected static ?string $model = Transaction::class;

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

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-banknotes'; }
    public static function getNavigationSort(): ?int { return 3; }
    public static function getNavigationGroup(): string|\UnitEnum|null { return 'العمليات'; }
    public static function getNavigationLabel(): string { return 'المعاملات'; }
    public static function getModelLabel(): string { return 'معاملة'; }
    public static function getPluralModelLabel(): string { return 'المعاملات'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
                ->required()
                ->live()
                ->afterStateUpdated(fn ($set) => $set('machine_id', null)),

            Select::make('machine_id')
                ->label('الآلة')
                ->options(fn ($get) => Machine::where('refinery_id', $get('refinery_id'))
                    ->where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->required()
                ->live()
                ->afterStateUpdated(function ($get, $set, ?string $state) {
                    if ($state) {
                        $machine = Machine::find($state);
                        $set('unit', $machine?->unit);
                        $set('price_per_unit', $machine?->price_per_unit);
                    }
                }),

            Select::make('worker_id')
                ->label('العامل')
                ->options(fn ($get) => Worker::where('refinery_id', $get('refinery_id'))
                    ->where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Select::make('sales_manager_id')
                ->label('مدير المبيعات')
                ->options(function () {
                    $user = Auth::user();
                    $query = User::where('role', 'sales_manager');
                    if (! $user->isSystemAdmin()) {
                        $query->where('refinery_id', $user->refinery_id);
                    }
                    return $query->pluck('name', 'id');
                })
                ->searchable()
                ->required(),

            Select::make('customer_id')
                ->label('العميل')
                ->relationship('customer', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->live()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('اسم العميل')
                        ->required(),
                    TextInput::make('phone_num')
                        ->label('رقم الهاتف')
                        ->tel(),
                    TextInput::make('address')
                        ->label('العنوان'),
                ])
                ->createOptionUsing(function (array $data, $get): int {
                    // Look for refinery_id in current or parent scope
                    $refineryId = $get('refinery_id') ?? $get('../../refinery_id');
                    
                    if (! $refineryId) {
                        throw new \Exception('الرجاء اختيار المصفاة أولاً قبل إضافة عميل جديد.');
                    }

                    $data['refinery_id'] = $refineryId;
                    
                    return Customer::create($data)->id;
                }),

            TextInput::make('unit')
                ->disabled()
                ->dehydrated()
                ->label('الوحدة (من الآلة)'),

            TextInput::make('price_per_unit')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->prefix('SDG')
                ->label('السعر لكل وحدة'),

            TextInput::make('quantity')
                ->label('الكمية')
                ->numeric()
                ->required()
                ->live()
                ->minValue(0.0001),

            Select::make('status')
                ->label('الحالة')
                ->options([
                    'pending' => 'قيد الانتظار', 
                    'completed' => 'مكتملة', 
                    'cancelled' => 'ملغاة'
                ])
                ->default('pending')
                ->required(),

            Textarea::make('notes')
                ->label('ملاحظات')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('refinery.name')->label('المصفاة')->searchable(),
                TextColumn::make('machine.name')->label('الآلة')->searchable(),
                TextColumn::make('worker.name')->label('العامل')->searchable(),
                TextColumn::make('customer.name')->label('العميل')->searchable(),
                TextColumn::make('salesManager.name')->label('مدير المبيعات'),
                TextColumn::make('unit')->label('الوحدة')->badge(),
                TextColumn::make('quantity')->label('الكمية')->numeric(4),
                TextColumn::make('total_amount')->label('الإجمالي')->money('SDG')->sortable(),
                TextColumn::make('status')->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default     => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغاة',
                        default     => 'قيد الانتظار',
                    }),
                TextColumn::make('created_at')->label('التاريخ')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('refinery')->label('المصفاة')->relationship('refinery', 'name'),
                SelectFilter::make('status')->label('الحالة')->options([
                    'pending' => 'قيد الانتظار', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة',
                ]),
            ])
                              ->actions([ViewAction::make(), EditAction::make()])

                        ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);

    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}