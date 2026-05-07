<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuditPassword
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('audit_authenticated')) {
            return redirect()->route('audits.auth');
        }

        return $next($request);
    }
}
