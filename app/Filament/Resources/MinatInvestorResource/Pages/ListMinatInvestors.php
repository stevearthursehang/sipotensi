<?php

namespace App\Filament\Resources\MinatInvestorResource\Pages;

use App\Filament\Resources\MinatInvestorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMinatInvestors extends ListRecords
{
    protected static string $resource = MinatInvestorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}