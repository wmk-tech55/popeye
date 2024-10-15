<?php

namespace MixCode\Policies;

use MixCode\User;
use MixCode\Addition;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdditionPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the addition.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Addition  $addition
     * @return mixed
     */
    public function view(User $user, Addition $addition)
    {
        return $this->isOwner($user, $addition);
    }

    /**
     * Determine whether the user can update the addition.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Addition  $addition
     * @return mixed
     */
    public function update(User $user, Addition $addition)
    {
        return $this->isOwner($user, $addition);
    }

    /**
     * Determine whether the user can delete the addition.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Addition  $addition
     * @return mixed
     */
    public function delete(User $user, Addition $addition)
    {
        return $this->isOwner($user, $addition);
    }

    /**
     * Determine whether the user can restore the addition.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Addition  $addition
     * @return mixed
     */
    public function restore(User $user, Addition $addition)
    {
        return $this->isOwner($user, $addition);
    }

    /**
     * Determine whether the user can permanently delete the addition.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Addition  $addition
     * @return mixed
     */
    public function forceDelete(User $user, Addition $addition)
    {
        return $this->isOwner($user, $addition);
    }

    /**
     * Determine whether the user is owner for addition or not.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Addition  $addition
     * @return bool
     */
    protected function isOwner($user, $addition)
    {
        return $user->id === $addition->creator_id;
    }
}
