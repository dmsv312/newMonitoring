<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Обработка входящего запроса.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role == User::ROLE_USER) {
            return response(
                ['error' => 'Unauthorized'],
                403
            );
        }

        return $next($request);
    }
}
