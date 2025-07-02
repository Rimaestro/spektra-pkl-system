<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

/**
 * Authentication API Controller for SPEKTRA PKL System
 * 
 * Handles user authentication operations including login, register,
 * logout, password reset, and token management.
 */
class AuthController extends BaseApiController
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'string', 'in:mahasiswa,dosen,pembimbing_lapangan'],
                'nim' => ['nullable', 'string', 'max:20', 'unique:users'],
                'nip' => ['nullable', 'string', 'max:20', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
            ]);

            // Validate role-specific fields
            if ($validated['role'] === 'mahasiswa' && empty($validated['nim'])) {
                return $this->validationErrorResponse([
                    'nim' => ['NIM is required for mahasiswa role']
                ]);
            }

            if (in_array($validated['role'], ['dosen', 'pembimbing_lapangan']) && empty($validated['nip'])) {
                return $this->validationErrorResponse([
                    'nip' => ['NIP is required for dosen and pembimbing_lapangan roles']
                ]);
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'], // Will be hashed by User model
                'role' => $validated['role'],
                'nim' => $validated['nim'] ?? null,
                'nip' => $validated['nip'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => 'active', // Default to active for new registrations
            ]);

            // Create API token
            $token = $user->createToken('api-token')->plainTextToken;

            return $this->createdResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'nim' => $user->nim,
                    'nip' => $user->nip,
                    'phone' => $user->phone,
                    'status' => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'User registered successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Authenticate user and return token
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'device_name' => ['nullable', 'string', 'max:255'],
            ]);

            // Rate limiting
            $key = Str::transliterate(Str::lower($validated['email']).'|'.$request->ip());
            
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                return $this->errorResponse(
                    "Too many login attempts. Please try again in {$seconds} seconds.",
                    429
                );
            }

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                RateLimiter::hit($key);
                return $this->unauthorizedResponse('Invalid credentials');
            }

            // Check if user is active
            if (!$user->isActive()) {
                return $this->forbiddenResponse('Account is not active');
            }

            // Check if account is locked
            if ($user->isLocked()) {
                return $this->forbiddenResponse('Account is locked');
            }

            // Clear rate limiter on successful login
            RateLimiter::clear($key);

            // Update login tracking
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_attempts' => 0,
            ]);

            // Create API token
            $deviceName = $validated['device_name'] ?? 'API Token';
            $token = $user->createToken($deviceName)->plainTextToken;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'nim' => $user->nim,
                    'nip' => $user->nip,
                    'phone' => $user->phone,
                    'status' => $user->status,
                    'last_login_at' => $user->last_login_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Login successful');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Login failed: ' . $e->getMessage());
        }
    }

    /**
     * Logout user and revoke token
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'Logout successful');

        } catch (\Exception $e) {
            return $this->errorResponse('Logout failed: ' . $e->getMessage());
        }
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            // Revoke all tokens
            $request->user()->tokens()->delete();

            return $this->successResponse(null, 'Logged out from all devices');

        } catch (\Exception $e) {
            return $this->errorResponse('Logout failed: ' . $e->getMessage());
        }
    }

    /**
     * Get authenticated user information
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'nim' => $user->nim,
            'nip' => $user->nip,
            'phone' => $user->phone,
            'address' => $user->address,
            'status' => $user->status,
            'avatar_url' => $user->avatar_url,
            'last_login_at' => $user->last_login_at,
            'created_at' => $user->created_at,
        ], 'User information retrieved');
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink($validated);

            if ($status === Password::RESET_LINK_SENT) {
                return $this->successResponse(null, 'Password reset link sent to your email');
            }

            return $this->errorResponse('Unable to send password reset link');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Password reset failed: ' . $e->getMessage());
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $status = Password::reset(
                $validated,
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => $password,
                        'password_changed_at' => now(),
                        'force_password_change' => false,
                    ])->save();

                    // Revoke all existing tokens
                    $user->tokens()->delete();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return $this->successResponse(null, 'Password reset successfully');
            }

            return $this->errorResponse('Password reset failed');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Password reset failed: ' . $e->getMessage());
        }
    }
}
