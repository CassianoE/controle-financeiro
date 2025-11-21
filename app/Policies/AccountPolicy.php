<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Account $account)
    {
        return $account->user_id === $user->id
            ? Response::allow()
            : Response::deny("Você não tem permissão para acessar esta conta.");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Account $account)
    {
        return $account->user_id === $user->id
            ? Response::allow()
            : Response::deny("Você não tem permissão para atualizar esta conta.",);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Account $account)
    {
        return $account->user_id === $user->id
            ? Response::allow()
            : Response::deny("Você não tem permissão para deletar esta conta.",);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Account $account): bool
    {
        return $account->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Account $account): bool
    {
        return $account->user_id === $user->id;
    }
}
