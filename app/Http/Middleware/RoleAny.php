<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cho phép truy cập nếu vai trò nằm trong danh sách.
 * Dùng: ->middleware(\App\Http\Middleware\RoleAny::class . ':admin,htqt')
 */
class RoleAny
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Không có quyền truy cập');
        }

        return $next($request);
    }
}
