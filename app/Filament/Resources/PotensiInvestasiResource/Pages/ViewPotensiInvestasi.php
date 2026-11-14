<?php

namespace App\Filament\Resources\PotensiInvestasiResource\Pages;

use App\Filament\Resources\PotensiInvestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPotensiInvestasi extends ViewRecord
{
    protected static string $resource = PotensiInvestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}