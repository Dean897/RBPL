<?php

namespace App\Policies;

use App\Models\Archive;
use App\Models\User;

class ArchivePolicy
{
    public function before(User $user, $ability)
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }
    }

    public function view(User $user, Archive $archive)
    {
        if (!$archive->is_private) {
            return true;
        }
        if ($archive->uploaded_by && $user->id === $archive->uploaded_by) {
            return true;
        }
        if ($archive->allowed_roles && is_array($archive->allowed_roles)) {
            return in_array($user->role ?? '', $archive->allowed_roles);
        }
        return false;
    }

    public function download(User $user, Archive $archive)
    {
        return $this->view($user, $archive);
    }

    public function create(User $user)
    {
        // allow any authenticated user to create; restrict via roles in app if needed
        return (bool) $user;
    }

    public function update(User $user, Archive $archive)
    {
        if ($user->id === $archive->uploaded_by) return true;
        return $this->view($user, $archive);
    }

    public function delete(User $user, Archive $archive)
    {
        if ($user->id === $archive->uploaded_by) return true;
        return isset($user->role) && $user->role === 'admin';
    }
}
