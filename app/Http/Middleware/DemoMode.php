<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoMode
{
    /**
     * Blocked HTTP methods in demo mode — writes are prevented.
     */
    protected array $blockedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Routes allowed even in demo mode (e.g., login/logout).
     */
    protected array $allowedRoutes = [
        'login',
        'loginPage',
        'logout',
        'home',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if (! $tenant || ! $tenant->is_demo) {
            return $next($request);
        }

        // Block write operations
        if (in_array($request->method(), $this->blockedMethods)) {
            $routeName = $request->route()?->getName();

            if (! in_array($routeName, $this->allowedRoutes)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Demo mode: write operations are disabled.',
                    ], 403);
                }

                return redirect()->back()->with('demo_blocked', 'This action is disabled in demo mode.');
            }
        }

        // Share demo flag with all views
        view()->share('isDemoMode', true);

        return $next($request);
    }
}
