<?php

namespace App\Filament\Resources\MinatInvestorResource\Pages;

use App\Filament\Resources\MinatInvestorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinatInvestor extends EditRecord
{
    protected static string $resource = MinatInvestorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}