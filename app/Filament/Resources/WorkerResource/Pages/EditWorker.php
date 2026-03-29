<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use App\Filament\Resources\WorkerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorker extends EditRecord
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
