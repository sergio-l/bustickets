<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Settings;
use App\Model\User;
use App\Model\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Settings $section, Setting $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function display(User $user)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function create(User $user)
    {
        return false;
    }

    public function edit(User $user)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function delete(User $user)
    {
        return false;
    }

}
