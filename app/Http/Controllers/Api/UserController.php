<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * User Management API Controller for SPEKTRA PKL System
 *
 * Handles CRUD operations for user management with role-based access control.
 * Only admin and koordinator can manage users.
 */
class UserController extends BaseApiController
{
    /**
     * Display a listing of users with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Apply search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            }

            // Apply role filter
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            // Apply status filter
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $limit = min($request->get('limit', $this->defaultLimit), $this->maxLimit);
            $users = $query->paginate($limit);

            // Transform response for consistent API format
            $response = [
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ];

            return $this->successResponse($response, 'Users retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve users: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created user
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $userData = $request->validated();
            $userData['password'] = Hash::make($userData['password']);
            $userData['password_changed_at'] = now();

            $user = User::create($userData);

            // Remove password from response
            $user->makeHidden(['password']);

            return $this->successResponse($user, 'User created successfully', 201);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user): JsonResponse
    {
        try {
            // Hide sensitive information
            $user->makeHidden(['password']);

            return $this->successResponse($user, 'User retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve user: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $updateData = $request->validated();

            // Don't allow updating password through this endpoint
            unset($updateData['password']);

            $user->update($updateData);
            $user->makeHidden(['password']);

            return $this->successResponse($user, 'User updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            // Prevent deleting own account
            if ($user->id === Auth::id()) {
                return $this->errorResponse('Cannot delete your own account', 422);
            }

            // Check if user has active PKL
            if ($user->pkls()->whereIn('status', ['pending', 'approved', 'ongoing'])->exists()) {
                return $this->errorResponse('Cannot delete user with active PKL', 422);
            }

            $user->delete();

            return $this->successResponse(null, 'User deleted successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete user: ' . $e->getMessage());
        }
    }
}
