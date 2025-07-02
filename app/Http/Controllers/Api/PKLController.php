<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreatePKLRequest;
use App\Http\Requests\UpdatePKLRequest;
use App\Models\PKL;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PKLController extends BaseApiController
{
    /**
     * Display a listing of PKL resources with role-based filtering
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $query = PKL::with(['user', 'company', 'supervisor', 'fieldSupervisor']);

            // Role-based filtering
            switch ($user->role) {
                case 'mahasiswa':
                    // Mahasiswa can only see their own PKL
                    $query->where('user_id', $user->id);
                    break;

                case 'dosen':
                    // Dosen can see PKL they supervise
                    $query->where('supervisor_id', $user->id);
                    break;

                case 'pembimbing_lapangan':
                    // Pembimbing lapangan can see PKL they supervise
                    $query->where('field_supervisor_id', $user->id);
                    break;

                case 'admin':
                case 'koordinator':
                    // Admin and koordinator can see all PKL
                    break;

                default:
                    return $this->forbiddenResponse('Unauthorized access');
            }

            // Apply filters
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('company_id')) {
                $query->where('company_id', $request->company_id);
            }

            if ($request->has('supervisor_id')) {
                $query->where('supervisor_id', $request->supervisor_id);
            }

            // Pagination
            $limit = min($request->get('limit', $this->defaultLimit), $this->maxLimit);
            $pkls = $query->paginate($limit);

            // Transform paginated response for consistent API format
            $response = [
                'data' => $pkls->items(),
                'pagination' => [
                    'current_page' => $pkls->currentPage(),
                    'last_page' => $pkls->lastPage(),
                    'per_page' => $pkls->perPage(),
                    'total' => $pkls->total(),
                    'from' => $pkls->firstItem(),
                    'to' => $pkls->lastItem(),
                ]
            ];

            return $this->successResponse($response, 'PKL list retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve PKL list: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created PKL resource
     */
    public function store(CreatePKLRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Only mahasiswa can create their own PKL, or admin/koordinator can create for others
            if ($user->role === 'mahasiswa') {
                $studentId = $user->id;
            } elseif (in_array($user->role, ['admin', 'koordinator']) && $request->has('student_id')) {
                $studentId = $request->student_id;
            } else {
                return $this->forbiddenResponse('Unauthorized to create PKL');
            }

            // Check if student already has active PKL
            $existingPKL = PKL::where('user_id', $studentId)
                             ->whereIn('status', ['pending', 'approved', 'ongoing'])
                             ->first();

            if ($existingPKL) {
                return $this->errorResponse('Student already has an active PKL', 422);
            }

            $pklData = $request->validated();
            $pklData['user_id'] = $studentId;
            $pklData['status'] = 'pending';

            $pkl = PKL::create($pklData);
            $pkl->load(['user', 'company', 'supervisor', 'fieldSupervisor']);

            return $this->successResponse($pkl, 'PKL created successfully', 201);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create PKL: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified PKL resource
     */
    public function show(PKL $pkl): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check access permissions
            if (!$this->canAccessPKL($user, $pkl)) {
                return $this->forbiddenResponse('Unauthorized to view this PKL');
            }

            $pkl->load(['user', 'company', 'supervisor', 'fieldSupervisor']);

            return $this->successResponse($pkl, 'PKL retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve PKL: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified PKL resource
     */
    public function update(UpdatePKLRequest $request, PKL $pkl): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check update permissions based on role and PKL status
            if (!$this->canUpdatePKL($user, $pkl, $request)) {
                return $this->forbiddenResponse('Unauthorized to update this PKL');
            }

            $updateData = $request->validated();

            // Handle status changes with proper authorization
            if ($request->has('status')) {
                $newStatus = $request->status;

                // Only koordinator/admin can approve/reject
                if (in_array($newStatus, ['approved', 'rejected']) && !in_array($user->role, ['admin', 'koordinator'])) {
                    return $this->forbiddenResponse('Only koordinator or admin can approve/reject PKL');
                }

                // Handle rejection reason
                if ($newStatus === 'rejected' && !$request->has('rejection_reason')) {
                    return $this->errorResponse('Rejection reason is required when rejecting PKL', 422);
                }

                // Handle supervisor assignment on approval
                if ($newStatus === 'approved' && $request->has('supervisor_id')) {
                    $updateData['supervisor_id'] = $request->supervisor_id;
                }
            }

            $pkl->update($updateData);
            $pkl->load(['user', 'company', 'supervisor', 'fieldSupervisor']);

            return $this->successResponse($pkl, 'PKL updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update PKL: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified PKL resource
     */
    public function destroy(PKL $pkl): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check delete permissions
            if (!$this->canDeletePKL($user, $pkl)) {
                return $this->forbiddenResponse('Unauthorized to delete this PKL');
            }

            // Only allow deletion of pending PKL
            if ($pkl->status !== 'pending') {
                return $this->errorResponse('Only pending PKL can be deleted', 422);
            }

            $pkl->delete();

            return $this->successResponse(null, 'PKL deleted successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete PKL: ' . $e->getMessage());
        }
    }

    /**
     * Check if user can access the PKL
     */
    private function canAccessPKL(User $user, PKL $pkl): bool
    {
        switch ($user->role) {
            case 'mahasiswa':
                return $pkl->user_id === $user->id;

            case 'dosen':
                return $pkl->supervisor_id === $user->id;

            case 'pembimbing_lapangan':
                return $pkl->field_supervisor_id === $user->id;

            case 'admin':
            case 'koordinator':
                return true;

            default:
                return false;
        }
    }

    /**
     * Check if user can update the PKL
     */
    private function canUpdatePKL(User $user, PKL $pkl, UpdatePKLRequest $request): bool
    {
        // Admin and koordinator can always update
        if (in_array($user->role, ['admin', 'koordinator'])) {
            return true;
        }

        // Mahasiswa can only update their own pending PKL
        if ($user->role === 'mahasiswa') {
            return $pkl->user_id === $user->id && $pkl->status === 'pending';
        }

        // Dosen can update PKL they supervise (limited fields)
        if ($user->role === 'dosen' && $pkl->supervisor_id === $user->id) {
            // Dosen can only update certain fields like notes, not core PKL data
            $allowedFields = ['notes', 'completion_notes'];
            $requestFields = array_keys($request->validated());
            return empty(array_diff($requestFields, $allowedFields));
        }

        return false;
    }

    /**
     * Check if user can delete the PKL
     */
    private function canDeletePKL(User $user, PKL $pkl): bool
    {
        // Admin and koordinator can always delete
        if (in_array($user->role, ['admin', 'koordinator'])) {
            return true;
        }

        // Mahasiswa can only delete their own pending PKL
        if ($user->role === 'mahasiswa') {
            return $pkl->user_id === $user->id && $pkl->status === 'pending';
        }

        return false;
    }
}
