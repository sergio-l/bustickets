<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Pages;
use App\Model\User;
use App\Model\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagesSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Pages $section, Page $item = null)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function display(User $user)
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user)
    {
        return $user->isSuperAdmin();
    }

    public function edit(User $user)
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user)
    {
        return $user->isSuperAdmin();
    }

}
