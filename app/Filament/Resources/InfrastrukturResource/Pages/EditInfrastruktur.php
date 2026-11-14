<?php

namespace App\Filament\Resources\InfrastrukturResource\Pages;

use App\Filament\Resources\InfrastrukturResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInfrastruktur extends EditRecord
{
    protected static string $resource = InfrastrukturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}