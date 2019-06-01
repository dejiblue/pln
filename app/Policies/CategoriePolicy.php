<?php

namespace App\Policies;

use App\User;
use App\Categorie;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Categorie $categorie)
    {

        return $user->id == $categorie->user_id ;
    }


    public function create(User $user)
    {
        return true ;
    }


    public function update(User $user, Categorie $categorie)
    {
        return $user->id == $categorie->user_id ;
    }


    public function delete(User $user, Categorie $categorie)
    {
        return $user->id == $categorie->user_id ;
    }
}
