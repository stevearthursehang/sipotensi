<?php

namespace App\Filament\Resources\KecamatanResource\Pages;

use App\Filament\Resources\KecamatanResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditKecamatan extends EditRecord
{
    protected static string $resource = KecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('recalculate')
                ->label('Hitung Ulang Koordinat & Luas')
                ->icon('heroicon-m-calculator')
                ->color('success')
                ->action(function () {
                    $this->record->recalculateCentroidAndArea();
                    $this->refreshFormData(['latitude', 'longitude', 'luas_total']);
                    Notification::make()
                        ->title('Berhasil Dihitung Ulang')
                        ->body('Titik pusat dan luas total berhasil dihitung ulang dari desa/kelurahan terkait.')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}

