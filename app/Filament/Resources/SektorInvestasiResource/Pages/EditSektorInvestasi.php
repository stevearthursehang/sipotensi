<?php

namespace App\Filament\Resources\SektorInvestasiResource\Pages;

use App\Filament\Resources\SektorInvestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSektorInvestasi extends EditRecord
{
    protected static string $resource = SektorInvestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}