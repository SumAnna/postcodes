<?php

namespace App\Http\Middleware;

use App\Enums\UserEnum;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse
    {
        if (!$request->user() || !in_array($request->user()->role, [UserEnum::ADMIN->value, UserEnum::MODERATOR->value])) {
            return redirect('/');
        }

        return $next($request);
    }


}
