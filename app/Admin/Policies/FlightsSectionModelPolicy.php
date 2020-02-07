<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Flights;
use App\Model\User;
use App\Model\Flight;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlightsSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Flights $section, Flight $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function display(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function edit(User $user)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function delete(User $user)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

}
