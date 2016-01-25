<?php
/**
 * User: Mani Wang
 * Date: 8/17/15
 * Time: 11:15 AM
 * Email: mani@forone.co
 */

namespace Forone\Middleware;

use Closure;

class EntrustPermission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions = null)
    {
        if ($permissions != null && !\Auth::user()->can(explode('|', $permissions))) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }

}