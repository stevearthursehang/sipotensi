<?php

namespace App\Filament\Resources\InfrastrukturResource\Pages;

use App\Filament\Resources\InfrastrukturResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInfrastrukturs extends ListRecords
{
    protected static string $resource = InfrastrukturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}