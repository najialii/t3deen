<?php

namespace App\Filament\Resources\RefineryResource\Pages;

use App\Filament\Resources\RefineryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRefinery extends EditRecord
{
    protected static string $resource = RefineryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
