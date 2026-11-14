<?php

namespace App\Filament\Resources\PotensiInvestasiResource\Pages;

use App\Filament\Resources\PotensiInvestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPotensiInvestasis extends ListRecords
{
    protected static string $resource = PotensiInvestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
