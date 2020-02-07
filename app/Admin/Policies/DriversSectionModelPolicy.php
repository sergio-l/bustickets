<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Drivers;
use App\Model\User;
use App\Model\Driver;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriversSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Drivers $section, Driver $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function display(User $user)
    {
        return true;
    }

    public function create(User $user, Drivers $section, Driver $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function edit(User $user, Drivers $section, Driver $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function delete(User $user, Drivers $section, Driver $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }


}
