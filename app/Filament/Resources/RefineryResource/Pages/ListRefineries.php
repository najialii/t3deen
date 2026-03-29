<?php

namespace App\Filament\Resources\RefineryResource\Pages;

use App\Filament\Resources\RefineryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRefineries extends ListRecords
{
    protected static string $resource = RefineryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
