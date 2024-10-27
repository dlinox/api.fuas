<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    protected $worker;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }

    public function loadDataTable(Request $request)
    {

        try {
            $positions = $this->worker
                ->select(
                    'id',
                    'document_number as documentNumber',
                    'name',
                    'paternal_last_name as paternalLastName',
                    'maternal_last_name as maternalLastName',
                    'birth_date as birthDate',
                    'gender',
                    'phone_number as phoneNumber',
                    'status',
                )
                ->dataTable($request);
            return ApiResponse::success($positions);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar datos de la tabla');
        }
    }

    public function save(Request $request)
    {
        try {
            if ($request->id != null) {
                $item = $this->worker->find($request->id);
                $item->update($request->all());
                return ApiResponse::success(null, 'Oficina actualizada con éxito');
            }
            $this->worker->create(
                [
                    'document_type' => 'DNI',
                    'document_number' => $request->documentNumber,
                    'name' => $request->name,
                    'paternal_last_name' => $request->paternalLastName,
                    'maternal_last_name' => $request->maternalLastName,
                    'birth_date' => $request->birthDate,
                    'gender' => $request->gender,
                    'phone_number' => $request->phoneNumber,
                    'email' => $request->email,
                    'status' => 1,
                    'position_id' => $request->positionId,
                    'office_id' => $request->officeId,

                ]
            );
            return ApiResponse::success(null, 'Oficina creada con éxito', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al crear la oficina');
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->worker->find($request->id)->delete();
            return ApiResponse::success(null, 'Oficina eliminada con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al eliminar la oficina');
        }
    }

    public function loadSelect(Request $request)
    {
        try {
            $items = $this->worker
                ->select(
                    'workers.id as value',
                    DB::raw(
                        "CONCAT_WS(' ', workers.document_number, '|' ,workers.name, workers.paternal_last_name, workers.maternal_last_name) as title"
                    ),
                    DB::raw('IF(users.worker_id IS NOT NULL, 1, 0) as hasUser')
                )
                ->leftJoin('users', 'workers.id', '=', 'users.worker_id')
                ->isEnabled()->get();
            return ApiResponse::success($items);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al cargar la lista de oficinas');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $this->worker->toggleStatus($request->id);
            return ApiResponse::success(null, 'Estado de la oficina actualizado con éxito');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al actualizar el estado de la oficina');
        }
    }
}
