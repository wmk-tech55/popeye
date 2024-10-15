<?php

namespace MixCode\Policies;

use MixCode\User;
use MixCode\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $this->isOwner($user, $order);
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return $this->isOwner($user, $order);
    }

    /**
     * Determine whether the user is owner for order or not.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Order  $order
     * @return bool
     */
    protected function isOwner($user, $order)
    {
        if ($user->isAdmin()) return true;
        
        return $user->id === $order->product_creator_id;
    }
}
