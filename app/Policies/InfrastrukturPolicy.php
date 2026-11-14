<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Infrastruktur;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfrastrukturPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Infrastruktur');
    }

    public function view(AuthUser $authUser, Infrastruktur $infrastruktur): bool
    {
        return $authUser->can('View:Infrastruktur');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Infrastruktur');
    }

    public function update(AuthUser $authUser, Infrastruktur $infrastruktur): bool
    {
        return $authUser->can('Update:Infrastruktur');
    }

    public function delete(AuthUser $authUser, Infrastruktur $infrastruktur): bool
    {
        return $authUser->can('Delete:Infrastruktur');
    }

    public function restore(AuthUser $authUser, Infrastruktur $infrastruktur): bool
    {
        return $authUser->can('Restore:Infrastruktur');
    }

    public function forceDelete(AuthUser $authUser, Infrastruktur $infrastruktur): bool
    {
        return $authUser->can('ForceDelete:Infrastruktur');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Infrastruktur');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Infrastruktur');
    }

    public function replicate(AuthUser $authUser, Infrastruktur $infrastruktur): bool
    {
        return $authUser->can('Replicate:Infrastruktur');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Infrastruktur');
    }

}