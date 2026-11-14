<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PotensiInvestasi;
use Illuminate\Auth\Access\HandlesAuthorization;

class PotensiInvestasiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PotensiInvestasi');
    }

    public function view(AuthUser $authUser, PotensiInvestasi $potensiInvestasi): bool
    {
        return $authUser->can('View:PotensiInvestasi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PotensiInvestasi');
    }

    public function update(AuthUser $authUser, PotensiInvestasi $potensiInvestasi): bool
    {
        return $authUser->can('Update:PotensiInvestasi');
    }

    public function delete(AuthUser $authUser, PotensiInvestasi $potensiInvestasi): bool
    {
        return $authUser->can('Delete:PotensiInvestasi');
    }

    public function restore(AuthUser $authUser, PotensiInvestasi $potensiInvestasi): bool
    {
        return $authUser->can('Restore:PotensiInvestasi');
    }

    public function forceDelete(AuthUser $authUser, PotensiInvestasi $potensiInvestasi): bool
    {
        return $authUser->can('ForceDelete:PotensiInvestasi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PotensiInvestasi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PotensiInvestasi');
    }

    public function replicate(AuthUser $authUser, PotensiInvestasi $potensiInvestasi): bool
    {
        return $authUser->can('Replicate:PotensiInvestasi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PotensiInvestasi');
    }

}