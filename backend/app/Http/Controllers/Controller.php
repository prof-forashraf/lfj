<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function dashboardRedirectPath(): string
    {
        $user = Auth::user();

        if ($user !== null && method_exists($user, 'isFilamentAdmin') && $user->isFilamentAdmin()) {
            return '/admin';
        }

        return '/dashboard';
    }
}
