<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    protected $office;

    public function __construct(Office $position)
    {
        $this->office = $position;
    }

    public function loadDataTable(Request $request)
    {
        try {
            $positions = $this->office->dataTable($request, ['name', 'description', 'status']);
            return ApiResponse::success($positions);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar datos de la tabla');
        }
    }

    public function save(Request $request)
    {
        try {
            if ($request->id != null) {
                $item = $this->office->find($request->id);
                $item->update($request->all());
                return ApiResponse::success(null, 'Oficina actualizada con éxito');
            }
            $this->office->create($request->all());
            return ApiResponse::success(null, 'Oficina creada con éxito', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al crear la oficina');
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->office->find($request->id)->delete();
            return ApiResponse::success(null, 'Oficina eliminada con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al eliminar la oficina');
        }
    }

    public function loadSelect(Request $request)
    {
        try {

            $positions = $this->office->isEnabled()->get(['id as value', 'name as title']);
            return ApiResponse::success($positions);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar la lista de oficinas');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $this->office->toggleStatus($request->id);
            return ApiResponse::success(null, 'Estado de la oficina actualizado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al actualizar el estado de la oficina');
        }
    }
}
