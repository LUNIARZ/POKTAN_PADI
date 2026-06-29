<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isRole(...$roles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Fitur ini tidak tersedia untuk peran akun Anda.',
                    'redirect' => $this->homeFor($user->peran),
                ], 409);
            }

            return redirect()
                ->to($this->homeFor($user->peran))
                ->with('status', 'Anda telah diarahkan ke halaman yang sesuai dengan akun Anda.');
        }

        return $next($request);
    }

    private function homeFor(string $role): string
    {
        return match ($role) {
            'admin' => route('admin'),
            'pembeli' => route('pembeli.marketplace'),
            default => route('dashboard'),
        };
    }
}
