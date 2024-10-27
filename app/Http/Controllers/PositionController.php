<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{

    protected $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    public function loadDataTable(Request $request)
    {
        try {
            $positions = $this->position->dataTable($request, ['name', 'description', 'status']);
            return ApiResponse::success($positions);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar los cargos');
        }
    }

    public function save(Request $request)
    {
        try {
            if ($request->id != null) {
                $position = $this->position->find($request->id);
                $position->update($request->all());
                return ApiResponse::success(null, 'Cargo actualizado con éxito');
            }
            $this->position->create($request->all());
            return ApiResponse::success(null, 'Cargo creado con éxito', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al crear el cargo');
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->position->find($request->id)->delete();
            return ApiResponse::success(null, 'Cargo eliminado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al eliminar el cargo');
        }
    }

    public function loadSelect(Request $request)
    {
        try {

            $positions = $this->position->isEnabled()->get(['id as value', 'name as title']);
            return ApiResponse::success($positions);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar los cargos');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $this->position->toggleStatus($request->id);
            return ApiResponse::success(null, 'Estado del cargo actualizado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al actualizar el estado del cargo');
        }
    }
}
