<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'category_id' => $request->categoryId,
                'status' => $request->status,
            ];

            if ($request->id != null) {
                $item = $this->service->find($request->id);
                $item->update($data);
                return ApiResponse::success(null, 'Servicio actualizado con éxito');
            }

            $this->service->create($data);
            return ApiResponse::success(null, 'Servicio creado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al guardar el servicio');
        }
    }
}
