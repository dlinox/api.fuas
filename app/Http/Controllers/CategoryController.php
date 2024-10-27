<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function loadDataTable(Request $request)
    {
        try {
            $items = $this->category->with('services')
            ->dataTable($request, ['name', 'type']);
            return ApiResponse::success($items);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar datos de la tabla');
        }
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status,
            ];

            if ($request->id != null) {
                $item = $this->category->find($request->id);
                $item->update($data);
                return ApiResponse::success(null, 'Categoría actualizada con éxito');
            }

            $this->category->create($data);
            return ApiResponse::success(null, 'Categoría creada con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al guardar la categoría');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $this->category->toggleStatus($request->id);
            return ApiResponse::success(null, 'Estado de la oficina actualizado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al actualizar el estado de la oficina');
        }
    }
}
