<?php

namespace App\Admin\Policies;

use App\Admin\Http\Sections\Orders;
use App\Model\User;
use App\Model\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrdersSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User   $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability, Orders $section, Order $item = null)
    {
        if ($user->isSuperAdmin() || $user->isManager()) {
            return true;
        }
    }

    public function display(User $user, Orders $section, Order $item)
    {
        return true;
    }

    public function create(User $user, Orders $section, Order $item)
    {
        return true;
        //return $user->isSuperAdmin() || $user->isManager();
    }

    public function edit(User $user, Orders $section, Order $item)
    {
        return true;
    }

    public function delete(User $user, Orders $section, Order $item)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

}
