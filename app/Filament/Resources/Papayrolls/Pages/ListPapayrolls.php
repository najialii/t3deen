<?php

namespace App\Filament\Resources\Papayrolls\Pages;

use App\Filament\Resources\Papayrolls\PapayrollResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPapayrolls extends ListRecords
{
    protected static string $resource = PapayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
