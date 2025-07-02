<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Base API Controller for SPEKTRA PKL System
 * 
 * Provides consistent API response formatting and common functionality
 * for all API controllers in the system.
 */
abstract class BaseApiController extends Controller
{
    /**
     * Default pagination limit
     */
    protected int $defaultLimit = 15;

    /**
     * Maximum pagination limit
     */
    protected int $maxLimit = 100;

    /**
     * Return a successful response
     */
    protected function successResponse(
        mixed $data = null, 
        string $message = 'Success', 
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a paginated response
     */
    protected function paginatedResponse(
        LengthAwarePaginator $paginator,
        string $message = 'Data retrieved successfully'
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ]
            ]
        ]);
    }

    /**
     * Return an error response
     */
    protected function errorResponse(
        string $message = 'An error occurred',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        array $errors = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error response
     */
    protected function validationErrorResponse(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return a not found response
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Return an unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a forbidden response
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a created response
     */
    protected function createdResponse(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return $this->successResponse($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Return a no content response
     */
    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get pagination parameters from request
     */
    protected function getPaginationParams(Request $request): array
    {
        $limit = (int) $request->get('limit', $this->defaultLimit);
        $page = (int) $request->get('page', 1);

        // Ensure limit is within bounds
        $limit = min($limit, $this->maxLimit);
        $limit = max($limit, 1);

        // Ensure page is valid
        $page = max($page, 1);

        return [
            'limit' => $limit,
            'page' => $page,
        ];
    }

    /**
     * Check if user has required role(s)
     */
    protected function hasRole(string|array $roles): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        $userRole = $user->role;
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        return in_array($userRole, $allowedRoles);
    }

    /**
     * Check if user owns the resource
     */
    protected function ownsResource(mixed $resource, string $userIdField = 'user_id'): bool
    {
        $user = auth()->user();
        
        if (!$user || !$resource) {
            return false;
        }

        return $user->id === $resource->{$userIdField};
    }

    /**
     * Check if user can access resource (owns it or has admin/koordinator role)
     */
    protected function canAccessResource(mixed $resource, string $userIdField = 'user_id'): bool
    {
        return $this->hasRole(['admin', 'koordinator']) || $this->ownsResource($resource, $userIdField);
    }

    /**
     * Transform collection to array with optional transformer
     */
    protected function transformCollection(Collection $collection, ?callable $transformer = null): array
    {
        if ($transformer) {
            return $collection->map($transformer)->toArray();
        }

        return $collection->toArray();
    }

    /**
     * Get search parameters from request
     */
    protected function getSearchParams(Request $request): array
    {
        return [
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'filters' => $request->get('filters', []),
        ];
    }
}
