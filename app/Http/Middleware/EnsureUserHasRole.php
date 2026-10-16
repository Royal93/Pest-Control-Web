<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Restrict a route to users with a given role on the 'web' guard (the
     * same users/login table customers and marketing/sales reps both use).
     * Usage: ->middleware('role:marketing') or ->middleware('role:customer').
     *
     * A logged-in user with the wrong role is sent to their own dashboard
     * rather than shown a blank "forbidden" page, since the likely cause is
     * just following an old link or bookmark, not snooping.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $actualRole = $user->role ?: 'customer';

        if ($actualRole !== $role) {
            return redirect()->route($actualRole === 'marketing' ? 'marketing.dashboard' : 'portal.index');
        }

        return $next($request);
    }
}
