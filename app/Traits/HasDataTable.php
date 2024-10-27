<?php

namespace App\Traits;

trait HasDataTable
{
    /**
     * Método para búsqueda dinámica en columnas específicas.
     */
    public function scopeSearch($query, $search, $columns = [])
    {
        return $query->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%$search%");
            }
        });
    }

    /**
     * Método para ordenar dinámicamente.
     */
    public function scopeSort($query, $sorts)
    {
        foreach ($sorts as $sort) {
            $query->orderBy($sort['key'], $sort['order']);
        }

        return $query;
    }

    /**
     * Método para obtener resultados en formato DataTable.
     */
    public static function scopeDataTable($query, $request, $searchColumns = [])
    {

        $itemsPerPage = $request->has('itemsPerPage') ? $request->itemsPerPage : 10;

        // Filtros dinámicos
        if ($request->has('filters') && is_array($request->filters)) {
            foreach ($request->filters as $filter => $value) {
                if (!is_null($value)) {
                    $query->where($filter, $value);
                }
            }
        }

        // Búsqueda dinámica en columnas especificadas
        if ($request->has('search')) {
            $searchColumns = $searchColumns ?: $query->getModel()->searchColumns;
            $query->search($request->search, $searchColumns);
        }

        // Ordenamiento dinámico
        if ($request->has('sortBy')) {
            $query->sort($request->sortBy);
        }

        return $query->paginate($itemsPerPage);
    }
}
