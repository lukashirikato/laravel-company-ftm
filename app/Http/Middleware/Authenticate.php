<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Jika akses ke /adm, redirect ke login admin
            if ($request->is('adm') || $request->is('adm/*')) {
                return route('admin.login.form');
            }
            // Default: login member
            return route('member.login.form');
        }
    }
}