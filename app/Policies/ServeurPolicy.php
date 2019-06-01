<?php

namespace App\Policies;

use App\User;
use App\serveur;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServeurPolicy
{
    use HandlesAuthorization;

    public function view(User $user, serveur $serveur)
    {
        return $serveur->user_id == $user->id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, serveur $serveur)
    {
        return $serveur->user_id == $user->id;
    }

    public function delete(User $user, serveur $serveur)
    {
        return $serveur->user_id == $user->id;
    }

}
