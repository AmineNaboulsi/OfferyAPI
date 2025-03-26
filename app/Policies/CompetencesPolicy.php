<?php

namespace App\Policies;

use App\Models\Competences;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompetencesPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Competences $competence): bool
    {
        return $user->id === $competence->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Competences $competence): bool
    {
        return $user->id === $competence->user_id;
    }

}
