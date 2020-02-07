<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Stations;
use App\Model\User;
use App\Model\Station;
use Illuminate\Auth\Access\HandlesAuthorization;

class StationsSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Stations $section, Station $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function display(User $user, Stations $section, Station $item)
    {
        return true;
    }

    public function create(User $user, Stations $section, Station $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function edit(User $user, Stations $section, Station $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function delete(User $user, Stations $section, Station $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

}
