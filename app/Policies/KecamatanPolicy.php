<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KecamatanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('ViewAny:Kecamatan');
    }

    public function view(User $user, Kecamatan $kecamatan): bool
    {
        return $user->hasRole('super_admin') || $user->can('View:Kecamatan');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Create:Kecamatan');
    }

    public function update(User $user, Kecamatan $kecamatan): bool
    {
        return $user->hasRole('super_admin') || $user->can('Update:Kecamatan');
    }

    public function delete(User $user, Kecamatan $kecamatan): bool
    {
        return $user->hasRole('super_admin') || $user->can('Delete:Kecamatan');
    }

    public function restore(User $user, Kecamatan $kecamatan): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Kecamatan $kecamatan): bool
    {
        return $user->hasRole('super_admin');
    }
}

