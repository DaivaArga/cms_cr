<?php

namespace App\Http\Controllers\Role;

use App\Helper\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(RoleRequest $request): JsonResponse
    {
        try {
            $query = Role::query();

            // Search
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Sort
            $sortBy = $request->input('sort_by', 'created_at');
            $sortDirection = $request->input('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $roles = $query->paginate($request->input('per_page', 15));

            return UserHelper::successResponse(
                'Berhasil mengambil data roles',
                new RoleCollection($roles)
            );
        } catch (\Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengambil data roles', 500);
        }
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(RoleRequest $request): JsonResponse
    {
        try {
            $role = Role::create($request->validated());

            return UserHelper::successResponse(
                'Berhasil menambahkan role baru',
                new RoleResource($role->load('users')),
                201
            );
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat menambahkan role', 500);
        }
    }

    /**
     * Display the specified role.
     */
    public function show($id): JsonResponse
    {
        try {
            $role = Role::with('users')->findOrFail($id);

            return UserHelper::successResponse(
                'Berhasil mengambil data role',
                new RoleResource($role)
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('Role tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error fetching role: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengambil data role', 500);
        }
    }

    /**
     * Update the specified role in storage.
     */
    public function update(RoleRequest $request, $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->update($request->validated());

            return UserHelper::successResponse(
                'Berhasil mengupdate data role',
                new RoleResource($role->load('users'))
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('Role tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengupdate role', 500);
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);

            // Check if role has users
            if ($role->users()->count() > 0) {
                return UserHelper::errorResponse('Role tidak dapat dihapus karena masih digunakan oleh user', 400);
            }

            $role->delete();

            return UserHelper::successResponse('Berhasil menghapus role', null, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('Role tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat menghapus role', 500);
        }
    }
}
