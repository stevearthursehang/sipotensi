<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SektorInvestasi;
use Illuminate\Auth\Access\HandlesAuthorization;

class SektorInvestasiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SektorInvestasi');
    }

    public function view(AuthUser $authUser, SektorInvestasi $sektorInvestasi): bool
    {
        return $authUser->can('View:SektorInvestasi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SektorInvestasi');
    }

    public function update(AuthUser $authUser, SektorInvestasi $sektorInvestasi): bool
    {
        return $authUser->can('Update:SektorInvestasi');
    }

    public function delete(AuthUser $authUser, SektorInvestasi $sektorInvestasi): bool
    {
        return $authUser->can('Delete:SektorInvestasi');
    }

    public function restore(AuthUser $authUser, SektorInvestasi $sektorInvestasi): bool
    {
        return $authUser->can('Restore:SektorInvestasi');
    }

    public function forceDelete(AuthUser $authUser, SektorInvestasi $sektorInvestasi): bool
    {
        return $authUser->can('ForceDelete:SektorInvestasi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SektorInvestasi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SektorInvestasi');
    }

    public function replicate(AuthUser $authUser, SektorInvestasi $sektorInvestasi): bool
    {
        return $authUser->can('Replicate:SektorInvestasi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SektorInvestasi');
    }

}