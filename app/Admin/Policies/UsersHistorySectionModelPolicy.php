<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Settings;
use App\Model\User;
use App\Model\UserHistory;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersHistorySectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function display(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function create(User $user)
    {
        return false;
    }

    public function edit(User $user)
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user)
    {
        return false;
    }

}
