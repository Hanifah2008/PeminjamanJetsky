<?php
/**
 * @file IDE Helper for Laravel
 * This file provides type hints for IDE autocomplete
 * It is not loaded at runtime
 */

namespace {
    use Illuminate\Contracts\Auth\Guard;
    use Illuminate\Contracts\Auth\StatefulGuard;

    /**
     * Get the currently authenticated user's ID
     *
     * @return mixed
     */
    function auth_id() {
        return auth()->id();
    }

    /**
     * Get the currently authenticated user
     *
     * @return \App\Models\User|null
     */
    function auth_user() {
        return auth()->user();
    }

    /**
     * Check if a user is authenticated
     *
     * @return bool
     */
    function auth_check() {
        return auth()->check();
    }

    /**
     * Get the authentication guard
     *
     * @param string|null $guard
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth(?string $guard = null) {}
}

namespace Illuminate\Support\Facades {
    /**
     * @method static bool check()
     * @method static \App\Models\User|null user()
     * @method static mixed id()
     * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard(string $name = null)
     * @see \Illuminate\Auth\AuthManager
     */
    class Auth
    {
    }
}
