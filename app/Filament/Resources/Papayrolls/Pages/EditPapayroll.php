<?php

namespace App\Filament\Resources\Papayrolls\Pages;

use App\Filament\Resources\Papayrolls\PapayrollResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPapayroll extends EditRecord
{
    protected static string $resource = PapayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
