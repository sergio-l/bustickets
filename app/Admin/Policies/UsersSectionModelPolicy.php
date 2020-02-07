<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Users;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Users $section, User $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }


    public function display(User $user, Users $section, User $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function create(User $user, Users $section, User $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function edit(User $user, Users $section, User $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function delete(User $user, Users $section, User $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }
}
