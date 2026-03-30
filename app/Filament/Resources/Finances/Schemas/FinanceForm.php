<?php

namespace App\Filament\Resources\Finances\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;    

class FinanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')->label('اسم القيد')->required()->maxLength(255),
                Select::make('type')
                    ->label('نوع القيد')
                    ->options([
                         'مدين',
                         'دائن',
                    ])
                    ->required(),
                TextInput::make('amount')
                    ->label('المبلغ')
                    ->numeric()
                    ->required(),
                TextInput::make('description')->label('الوصف')->maxLength(65535),
                
                Select::make('refinery_id')
                    ->label('المصفاة')
                    ->options(function () {
                        $user = Auth::user();
                        if ($user?->isRefineryAdmin()) {
                            return $user->refinery ? [$user->refinery->id => $user->refinery->name] : [];
                        }
                        return \App\Models\Refinery::pluck('name', 'id')->toArray();
                    })  
                    ->required(),

                    TextInput::make('category')->label('الفئة')->required()->maxLength(255),
                    TextInput::make('payment_method')->label('طريقة الدفع')->required()->maxLength(255),
                    TextInput::make('entry_date')->label('تاريخ القيد')->type('date')->required(),




                    Select::make('user_id')
                    ->label('المستخدم')
                    ->options(function () {
                        return \App\Models\User::pluck('name', 'id')->toArray();
                        })
                        ->required(),       

                        Toggle::make('is_active')->label('نشط')->default(true),         
                ]);

    }
}
