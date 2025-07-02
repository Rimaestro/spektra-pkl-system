<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Role-based access control middleware for SPEKTRA PKL system
 *
 * Supports multiple roles: admin, koordinator, dosen, mahasiswa, pembimbing_lapangan
 *
 * Usage:
 * Route::get('/admin', function () {
 *     // Only admin can access
 * })->middleware('role:admin');
 *
 * Route::get('/management', function () {
 *     // Admin or koordinator can access
 * })->middleware('role:admin,koordinator');
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): SymfonyResponse
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return $this->unauthorizedResponse($request, 'Authentication required');
        }

        $user = $request->user();

        // Check if user has any of the required roles
        if (!$this->hasAnyRole($user, $roles)) {
            return $this->forbiddenResponse(
                $request,
                'Insufficient permissions. Required roles: ' . implode(', ', $roles)
            );
        }

        return $next($request);
    }

    /**
     * Check if user has any of the specified roles
     */
    private function hasAnyRole($user, array $roles): bool
    {
        if (empty($roles)) {
            return true;
        }

        $userRole = $user->role;

        // Normalize roles to handle comma-separated string
        $allowedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, explode(',', $role));
            } else {
                $allowedRoles[] = $role;
            }
        }

        // Trim whitespace from roles
        $allowedRoles = array_map('trim', $allowedRoles);

        return in_array($userRole, $allowedRoles);
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
            ->with('error', $message);
    }

    /**
     * Return forbidden response (403)
     */
    private function forbiddenResponse(Request $request, string $message): SymfonyResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'error' => 'Forbidden'
            ], Response::HTTP_FORBIDDEN);
        }

        // For web requests, redirect to dashboard with error message
        return redirect()->route('dashboard')
            ->with('error', $message);
    }
}
