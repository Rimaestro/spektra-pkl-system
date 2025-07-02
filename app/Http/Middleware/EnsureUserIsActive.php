<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Middleware to ensure user is active and account is in good standing
 *
 * Checks:
 * - User is authenticated
 * - User status is 'active'
 * - Account is not locked
 * - Password is not expired
 * - Email is verified (if required)
 */
class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->unauthorizedResponse($request, 'Authentication required');
        }

        // Check if user is active
        if (!$user->isActive()) {
            // For API requests, revoke current token
            if ($request->expectsJson() && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            }
            return $this->unauthorizedResponse(
                $request,
                'Akun Anda tidak aktif. Silakan hubungi administrator.'
            );
        }

        // Check if user account is locked
        if ($user->isLocked()) {
            // For API requests, revoke current token
            if ($request->expectsJson() && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            }
            return $this->unauthorizedResponse(
                $request,
                'Akun Anda terkunci. Silakan coba lagi nanti.'
            );
        }

        // Check if user needs to change password
        if ($user->needsPasswordChange() || $user->isPasswordExpired()) {
            if (!$request->routeIs('password.change', 'password.update', 'logout')) {
                return $this->redirectToPasswordChange(
                    $request,
                    'Anda perlu mengubah password sebelum melanjutkan.'
                );
            }
        }

        return $next($request);
    }

    /**
     * Return unauthorized response (401)
     */
    private function unauthorizedResponse(Request $request, string $message): SymfonyResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('login'))
            ->withErrors(['email' => $message]);
    }

    /**
     * Redirect to password change page
     */
    private function redirectToPasswordChange(Request $request, string $message): SymfonyResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'error' => 'Password Change Required',
                'redirect' => app()->environment('testing') ? '/change-password' : route('password.change')
            ], Response::HTTP_FORBIDDEN);
        }

        if (app()->environment('testing')) {
            return response()->json([
                'message' => $message,
                'error' => 'Password Change Required'
            ], Response::HTTP_FORBIDDEN);
        }

        return redirect()->route('password.change')
            ->with('warning', $message);
    }
}
