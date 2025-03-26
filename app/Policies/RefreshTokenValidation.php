<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class RefreshTokenValidation
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }
    /**
     * Determine whether the user can view any models.
     */
    public function VerifyrefreshToken(User $user): bool
    {
        // try {
        //     $user = JWTAuth::parseToken()->authenticate();

        //     $payload = JWTAuth::getPayload();

        //     if ($payload->get('type') && $payload->get('type') === 'refresh') {
        //         return false;
        //     }

        //     return true;

        // } catch (\Exception $e) {
        //     return false;
        // }
        return true;
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
