<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Pet $pet)
    {
        return $user->id === $pet->user_id;
    }

    public function update(User $user, Pet $pet)
    {
        return $user->id === $pet->user_id;
    }

    public function delete(User $user, Pet $pet)
    {
        return $user->id === $pet->user_id;
    }
}