<?php

namespace App\Filament\Resources\Payrolls\Schemas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Forms\Components\MorphToSelect;
use Filament\Schemas\Schema;
use App\Models\Worker;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\User;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               MorphToSelect::make('payable')
                ->label('المستلم')
                ->types([
                    MorphToSelect\Type::make(Worker::class)->titleAttribute('name')->label('عامل'),
                    MorphToSelect\Type::make(User::class)->titleAttribute('name')->label('مدير المبيعات'),
                ])
                ->searchable()
                ->required()
                ->columnSpanFull(),

            DatePicker::make('period_start')->label('بداية الفترة')->required(),
            DatePicker::make('period_end')->label('نهاية الفترة')->required(),

            
            TextInput::make('base_salary')
                ->label('الراتب الأساسي  (الإجمالي)')
                ->required()
                ->numeric()
                ->live() 
                ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateNet($set, $get)),

            TextInput::make('deductions')
                ->label('الخصومات')
                ->required()
                ->numeric()
                ->default(0)
                ->live()
                ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateNet($set, $get)),

            TextInput::make('net_pay')
                ->label('الصافي')
                ->required()
                ->numeric()
                ->readOnly(),
                // ->helperText('   يتم احتسابه تلقائياً (الإجمالي - الخصومات)'),

                Select::make('refinery_id')
                    ->hidden()
                    ->default(fn () => auth()->user()->refinery_id)
                    ->label('المنشأة')
                    ->required(),

            TextInput::make('payment_method')->label('طريقة الدفع')->required(),
            TextInput::make('notes')->label('ملاحظات')->maxLength(255),
            DatePicker::make('paid_at')->label('تاريخ الدفع')->required(),
        
            ]);
    }

    public static function calculateNet(Set $set, Get $get): void
    {
        $amount = (float) $get('base_salary') ?? 0;
        $deductions = (float) $get('deductions') ?? 0;
        
        $set('net_pay', number_format($amount - $deductions, 2, '.', ''));
    }
}
