<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MinatInvestor;
use Illuminate\Auth\Access\HandlesAuthorization;

class MinatInvestorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MinatInvestor');
    }

    public function view(AuthUser $authUser, MinatInvestor $minatInvestor): bool
    {
        return $authUser->can('View:MinatInvestor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MinatInvestor');
    }

    public function update(AuthUser $authUser, MinatInvestor $minatInvestor): bool
    {
        return $authUser->can('Update:MinatInvestor');
    }

    public function delete(AuthUser $authUser, MinatInvestor $minatInvestor): bool
    {
        return $authUser->can('Delete:MinatInvestor');
    }

    public function restore(AuthUser $authUser, MinatInvestor $minatInvestor): bool
    {
        return $authUser->can('Restore:MinatInvestor');
    }

    public function forceDelete(AuthUser $authUser, MinatInvestor $minatInvestor): bool
    {
        return $authUser->can('ForceDelete:MinatInvestor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MinatInvestor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MinatInvestor');
    }

    public function replicate(AuthUser $authUser, MinatInvestor $minatInvestor): bool
    {
        return $authUser->can('Replicate:MinatInvestor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MinatInvestor');
    }

}