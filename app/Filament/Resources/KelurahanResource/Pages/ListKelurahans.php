<?php

namespace App\Filament\Resources\KelurahanResource\Pages;

use App\Filament\Resources\KelurahanResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListKelurahans extends ListRecords
{
    protected static string $resource = KelurahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function modifyQueryUsing(Builder $query): Builder
    {
        /** @var User|null $user */
        $user = filament()->auth()->user();
        if ($user && $user->hasRole('Admin Kelurahan') && $user->kelurahan_id) {
            $query->where('id', $user->kelurahan_id);
        }

        return $query;
    }
}
