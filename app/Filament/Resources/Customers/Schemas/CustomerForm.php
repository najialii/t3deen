<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Refinery;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('اسم العميل')
                ->required()
                ->maxLength(255),

            TextInput::make('phone_num')
                ->label('رقم الهاتف')
                ->tel()
                ->maxLength(20),

            Textarea::make('address')
                ->label('العنوان')
                ->columnSpanFull(),

            Select::make('refinery_id')
                ->label('المصفاة')
                ->options(function () {
                    $user = Auth::user();
                    
                    if ($user->role === 'system_admin') {
                        return Refinery::pluck('name', 'id');
                    }

                    return Refinery::where('id', $user->refinery_id)->pluck('name', 'id');
                })
                ->default(fn () => Auth::user()->refinery_id)
                ->searchable()
                ->required(),
        ]);
    }
}