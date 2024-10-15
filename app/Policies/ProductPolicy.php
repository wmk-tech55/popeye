<?php

namespace MixCode\Policies;

use MixCode\User;
use MixCode\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the product.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Product  $product
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
        return $this->isOwner($user, $product);
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Product  $product
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        return $this->isOwner($user, $product);
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Product  $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        return $this->isOwner($user, $product);
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Product  $product
     * @return mixed
     */
    public function restore(User $user, Product $product)
    {
        return $this->isOwner($user, $product);
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Product  $product
     * @return mixed
     */
    public function forceDelete(User $user, Product $product)
    {
        if ($user->isAdmin()) return true;
        return $this->isOwner($user, $product);
    }

    /**
     * Determine whether the user is owner for product or not.
     *
     * @param  \MixCode\User  $user
     * @param  \MixCode\Product  $product
     * @return bool
     */
    protected function isOwner($user, $product)
    {
        if ($user->isAdmin()) return true;
        
        return $user->id === $product->creator_id;
    }
}
