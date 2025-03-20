<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefreshTokenValidation
{
    use HandlesAuthorization;

    /**
     * Determine if the user has a valide refresh token.
     *
     * @param  mixed  $user
     * @param  string $refreshToken
     * @return bool
     */
    public function validateRefreshToken($user = null, string $refreshToken): bool
    {
        try {
            auth()->setToken($refreshToken);
            return auth()->check();
        } catch (\Exception $e) {
            return false;
        }
    }

}
