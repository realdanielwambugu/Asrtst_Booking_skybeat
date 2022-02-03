<?php

Namespace auth\access\policies;

use models\User;

use models\Booking;

use support\proxies\Response;

class BookingPolicy
{
    /**
    * check if the user can view the given $booking
    *
    * @return bool;
    */
    public function show(User $user, Booking $booking)
    {
       return $user->id === $booking->user_id || $user->isAdmin;
    }

    /**
    * check if the user can create new $booking
    *
    * @return bool;
    */
    public function create(User $user)
    {
       return $user->isAdmin();
    }

    /**
    * check if the user can update the given $booking
    *
    * @return bool;
    */
    public function update(User $user, Booking $booking)
    {
       return $user->id === $booking->user_id;
             // ? Response::allow()
             // : Response::deny('You do not own this booking.');
    }


    /**
    * check if the user can delete the given $booking
    *
    * @return bool;
    */
    public function delete(User $user, Booking $booking)
    {
       return $user->id === $booking->user_id;
    }
}
