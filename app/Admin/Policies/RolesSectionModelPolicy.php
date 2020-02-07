<?php
namespace App\Admin\Policies;
use App\Admin\Http\Sections\Roles;
use App\Model\User;
use App\Model\Role;
use Illuminate\Auth\Access\HandlesAuthorization;
class RolesSectionModelPolicy
{
    use HandlesAuthorization;
    /**
     * @param User $user
     * @param string $ability
     * @param Roles $section
     * @param Role $item
     *
     * @return bool
     */
    public function before(User $user, $ability, Roles $section, Role $item)
    {
        if ($user->isSuperAdmin()) {
            if ($ability != 'display') {
                return false;
            }
            return true;
        }
    }
    /**
     * @param User $user
     * @param Roles $section
     * @param Role $item
     *
     * @return bool
     */
    public function display(User $user, Roles $section, Role $item)
    {
        return $user->isSuperAdmin();
    }

}