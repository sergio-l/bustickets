<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Buses;
use App\Model\User;
use App\Model\Bus;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusesSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Buses $section, Bus $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function display(User $user, Buses $section, Bus $item)
    {
        return true;
    }

    public function create(User $user, Buses $section, Bus $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function edit(User $user, Buses $section, Bus $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function delete(User $user, Buses $section, Bus $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

}
