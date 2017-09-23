<?php

namespace App\Policies;

use App\Portfolio;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function save(User $user){
        return $user->canDo('ADD_ARTICLES');
    }

    public function edit(User $user){
        return $user->canDo('UPDATE_ARTICLES');
    }

    public function destroy(User $user, Portfolio $portfolio){
        return ($user->canDo('DELETE_ARTICLES') && $portfolio->user_id = $user->id);
    }
}
