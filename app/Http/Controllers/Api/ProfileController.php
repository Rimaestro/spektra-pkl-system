<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

/**
 * Profile Management API Controller for SPEKTRA PKL System
 *
 * Handles user profile operations including profile updates and password changes.
 */
class ProfileController extends BaseApiController
{
    /**
     * Update user profile
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $user->update($validated);
            $user->makeHidden(['password']);

            return $this->successResponse($user, 'Profile updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return $this->validationErrorResponse([
                    'current_password' => ['Password saat ini tidak benar.']
                ]);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => now(),
                'force_password_change' => false,
            ]);

            return $this->successResponse(null, 'Password changed successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to change password: ' . $e->getMessage());
        }
    }
}
