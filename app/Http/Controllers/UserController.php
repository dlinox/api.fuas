<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function loadDataTable(Request $request)
    {
        try {
            $items = $this->user
                ->select(
                    'id',
                    'full_name as fullName',
                    'email',
                    'worker_id as workerId',
                    DB::raw('(select group_concat(permission_id) from model_has_permissions where model_id = users.id) as permissions'),
                    'status',
                )
                ->dataTable($request, ['full_name', 'email']);

            $items->map(function ($item) {
                $item->permissions = array_map('intval', explode(',', $item->permissions));
                return $item;
            });

            return ApiResponse::success($items);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar datos de la tabla');
        }
    }

    public function save(Request $request)
    {
        try {
            $workerFullName =  Worker::find($request->workerId)->full_name;
            $data = [
                'full_name' => strtoupper($workerFullName),
                'email' => $request->email,
                'worker_id' => $request->workerId,
                'status' => $request->status,
            ];

            if ($request->id != null) {
                $item = $this->user->find($request->id);
                $item->update($data);
                return ApiResponse::success(null, 'Oficina actualizada con éxito');
            }
            $data['password'] = $request->password;
            $user = $this->user->create($data);
            $user->assignRole('user');
            return ApiResponse::success(null, 'Oficina creada con éxito', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al crear la oficina');
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->user->find($request->id)->delete();
            return ApiResponse::success(null, 'Oficina eliminada con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al eliminar la oficina');
        }
    }

    public function loadSelect(Request $request)
    {
        try {

            $positions = $this->user->isEnabled()->get(['id', 'name']);
            return ApiResponse::success($positions);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar la lista de oficinas');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $this->user->toggleStatus($request->id);
            return ApiResponse::success(null, 'Estado de la oficina actualizado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al actualizar el estado de la oficina');
        }
    }

    //gel all permissions
    public function allPermissions(Request $request)
    {

        try {
            $permissions = Permission::select('id', 'description', 'parent_id as parentId')
                ->get();

            $parents = $permissions->where('parentId', null);

            $permissions = $parents->map(function ($parent) use ($permissions) {
                $children = $permissions->where('parentId', $parent->id)->values();
                $parent->children = $children;
                return $parent;
            })->values();

            return ApiResponse::success($permissions->toArray());
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar los permisos');
        }
    }

    //asiign permissions
    public function assignPermissions(Request $request)
    {
        try {
            $user = $this->user->find($request->userId);
            $user->syncPermissions($request->permissions);
            return ApiResponse::success(null, 'Permisos asignados con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al asignar permisos');
        }
    }
}
