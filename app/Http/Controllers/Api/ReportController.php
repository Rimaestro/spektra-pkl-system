<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use App\Models\PKL;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

/**
 * Report Management API Controller for SPEKTRA PKL System
 *
 * Handles CRUD operations for PKL reports with role-based access control.
 */
class ReportController extends BaseApiController
{
    /**
     * Display a listing of reports with role-based filtering
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $query = Report::with(['pkl.user', 'pkl.company', 'user']);

            // Role-based filtering
            switch ($user->role) {
                case 'mahasiswa':
                    // Mahasiswa can only see their own reports
                    $query->where('user_id', $user->id);
                    break;

                case 'dosen':
                    // Dosen can see reports from PKL they supervise
                    $query->whereHas('pkl', function ($q) use ($user) {
                        $q->where('supervisor_id', $user->id);
                    });
                    break;

                case 'pembimbing_lapangan':
                    // Pembimbing lapangan can see reports from PKL they supervise
                    $query->whereHas('pkl', function ($q) use ($user) {
                        $q->where('field_supervisor_id', $user->id);
                    });
                    break;

                case 'admin':
                case 'koordinator':
                    // Admin and koordinator can see all reports
                    break;

                default:
                    return $this->forbiddenResponse('Unauthorized access');
            }

            // Apply filters
            if ($request->has('type')) {
                $query->where('report_type', $request->type);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('pkl_id')) {
                $query->where('pkl_id', $request->pkl_id);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $limit = min($request->get('limit', $this->defaultLimit), $this->maxLimit);
            $reports = $query->paginate($limit);

            // Transform response for consistent API format
            $response = [
                'data' => $reports->items(),
                'pagination' => [
                    'current_page' => $reports->currentPage(),
                    'last_page' => $reports->lastPage(),
                    'per_page' => $reports->perPage(),
                    'total' => $reports->total(),
                    'from' => $reports->firstItem(),
                    'to' => $reports->lastItem(),
                ]
            ];

            return $this->successResponse($response, 'Reports retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve reports: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'pkl_id' => [
                    'required',
                    'integer',
                    Rule::exists('pkls', 'id')
                ],
                'report_type' => [
                    'required',
                    'string',
                    Rule::in(['daily', 'weekly', 'monthly', 'final'])
                ],
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'report_date' => 'sometimes|date',
                'status' => [
                    'sometimes',
                    'string',
                    Rule::in(['draft', 'submitted', 'reviewed', 'approved', 'rejected'])
                ]
            ]);

            // Verify user can create report for this PKL
            $pkl = PKL::findOrFail($validated['pkl_id']);

            if ($user->role === 'mahasiswa' && $pkl->user_id !== $user->id) {
                return $this->forbiddenResponse('You can only create reports for your own PKL');
            }

            if (!in_array($user->role, ['mahasiswa', 'admin', 'koordinator'])) {
                return $this->forbiddenResponse('Only mahasiswa, admin, or koordinator can create reports');
            }

            $validated['user_id'] = $user->id;
            $validated['status'] = $validated['status'] ?? 'draft';
            $validated['report_date'] = $validated['report_date'] ?? now()->toDateString();

            $report = Report::create($validated);
            $report->load(['pkl.user', 'pkl.company', 'user']);

            return $this->successResponse($report, 'Report created successfully', 201);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create report: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified report
     */
    public function show(Report $report): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check access permissions
            if (!$this->canAccessReport($user, $report)) {
                return $this->forbiddenResponse('Unauthorized to view this report');
            }

            $report->load(['pkl.user', 'pkl.company', 'user']);

            return $this->successResponse($report, 'Report retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve report: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified report
     */
    public function update(Request $request, Report $report): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check update permissions
            if (!$this->canUpdateReport($user, $report)) {
                return $this->forbiddenResponse('Unauthorized to update this report');
            }

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'status' => [
                    'sometimes',
                    'string',
                    Rule::in(['draft', 'submitted', 'reviewed', 'approved', 'rejected'])
                ],
                'feedback' => 'sometimes|string|max:1000'
            ]);

            // Business rules for status changes
            if ($request->has('status')) {
                $newStatus = $request->status;

                // Only supervisors can provide feedback and change status to reviewed/approved/rejected
                if (in_array($newStatus, ['reviewed', 'approved', 'rejected']) &&
                    !in_array($user->role, ['dosen', 'pembimbing_lapangan', 'admin', 'koordinator'])) {
                    return $this->forbiddenResponse('Only supervisors can review reports');
                }

                // Mahasiswa can only change from draft to submitted
                if ($user->role === 'mahasiswa' &&
                    !($report->status === 'draft' && $newStatus === 'submitted')) {
                    return $this->forbiddenResponse('You can only submit draft reports');
                }
            }

            $report->update($validated);
            $report->load(['pkl.user', 'pkl.company', 'user']);

            return $this->successResponse($report, 'Report updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update report: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified report
     */
    public function destroy(Report $report): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check delete permissions
            if (!$this->canDeleteReport($user, $report)) {
                return $this->forbiddenResponse('Unauthorized to delete this report');
            }

            // Only allow deletion of draft reports
            if ($report->status !== 'draft') {
                return $this->errorResponse('Only draft reports can be deleted', 422);
            }

            $report->delete();

            return $this->successResponse(null, 'Report deleted successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete report: ' . $e->getMessage());
        }
    }

    /**
     * Check if user can access the report
     */
    private function canAccessReport($user, Report $report): bool
    {
        switch ($user->role) {
            case 'mahasiswa':
                return $report->user_id === $user->id;

            case 'dosen':
                return $report->pkl->supervisor_id === $user->id;

            case 'pembimbing_lapangan':
                return $report->pkl->field_supervisor_id === $user->id;

            case 'admin':
            case 'koordinator':
                return true;

            default:
                return false;
        }
    }

    /**
     * Check if user can update the report
     */
    private function canUpdateReport($user, Report $report): bool
    {
        // Admin and koordinator can always update
        if (in_array($user->role, ['admin', 'koordinator'])) {
            return true;
        }

        // Mahasiswa can only update their own draft reports
        if ($user->role === 'mahasiswa') {
            return $report->user_id === $user->id && $report->status === 'draft';
        }

        // Supervisors can update reports for PKL they supervise
        if ($user->role === 'dosen') {
            return $report->pkl->supervisor_id === $user->id;
        }

        if ($user->role === 'pembimbing_lapangan') {
            return $report->pkl->field_supervisor_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can delete the report
     */
    private function canDeleteReport($user, Report $report): bool
    {
        // Admin and koordinator can always delete
        if (in_array($user->role, ['admin', 'koordinator'])) {
            return true;
        }

        // Mahasiswa can only delete their own draft reports
        if ($user->role === 'mahasiswa') {
            return $report->user_id === $user->id && $report->status === 'draft';
        }

        return false;
    }
}
