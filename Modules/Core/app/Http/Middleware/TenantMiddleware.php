<?php
// app/Http/Middleware/TenantMiddleware.php

namespace Modules\Core\Http\Middleware;

use Modules\Core\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
   // app/Http/Middleware/TenantMiddleware.php

public function handle(Request $request, Closure $next)
{
    $host = $request->getHost();
    $subdomain = explode('.', $host)[0];

    // لو شغال local بـ 127.0.0.1 أو localhost
    if (in_array($host, ['localhost', '127.0.0.1'])) {
        $subdomain = 'demo'; // fallback للـ development
    }

    $tenant = Tenant::where('domain', $subdomain)
                    ->where('is_active', true)
                    ->first();

    if (!$tenant) {
        abort(404, 'Tenant not found');
    }

    app()->instance('currentTenant', $tenant);

    return $next($request);
}
}