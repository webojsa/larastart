<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function view(User $user, Order $order): Response
    {
        return $user->id === $order->user_id ?
            Response::allow():
            Response::deny("You dont own this order");
    }

    /**
     * Determine whether the user can modify the model.
     */
    public function modify(User $user, Order $order): Response
    {
        return $user->isAdmin()
           ? Response::allow()
           : Response::deny("Not allowed");
    }


}
