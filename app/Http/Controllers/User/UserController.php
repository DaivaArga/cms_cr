<?php

namespace App\Http\Controllers\User;

use App\Helper\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserListRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(UserListRequest $request): JsonResponse
    {
        try {
            $query = User::query()->with('role');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by role
            if ($request->has('role_id')) {
                $query->where('role_id', $request->role_id);
            }

            // Search
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Sort
            $sortBy = $request->input('sort_by', 'created_at');
            $sortDirection = $request->input('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $users = $query->paginate($request->input('per_page', 15));

            return UserHelper::successResponse(
                'Berhasil mengambil data users',
                new UserCollection($users)
            );
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengambil data users', 500);
        }
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role_id' => $request->role_id,
                'status' => $request->status ?? 'active',
            ]);

            return UserHelper::successResponse(
                'Berhasil menambahkan user baru',
                new UserResource($user->load('role')),
                201
            );
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat menambahkan user', 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::with('role')->findOrFail($id);

            return UserHelper::successResponse(
                'Berhasil mengambil data user',
                new UserResource($user)
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('User tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengambil data user', 500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->only(['name', 'email', 'phone', 'role_id', 'status']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return UserHelper::successResponse(
                'Berhasil mengupdate data user',
                new UserResource($user->load('role'))
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('User tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengupdate user', 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return UserHelper::successResponse('Berhasil menghapus user', null, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('User tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat menghapus user', 500);
        }
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id): JsonResponse
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return UserHelper::successResponse(
                'Berhasil mengembalikan user yang dihapus',
                new UserResource($user->load('role'))
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('User tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error restoring user: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat mengembalikan user', 500);
        }
    }

    /**
     * Permanently delete a soft deleted user.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->forceDelete();

            return UserHelper::successResponse('Berhasil menghapus user secara permanen', null, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return UserHelper::errorResponse('User tidak ditemukan', 404);
        } catch (\Exception $e) {
            Log::error('Error force deleting user: ' . $e->getMessage());
            return UserHelper::errorResponse('Terjadi kesalahan saat menghapus user', 500);
        }
    }
}
