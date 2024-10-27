<?php

namespace App\Traits;

trait HasTransformAttributes
{
    /**
     * Convertir un nombre de atributo a camelCase.
     *
     * @param  string  $key
     * @return string
     */
    protected function toCamelCase($key)
    {
        return lcfirst(str_replace('_', '', ucwords($key, '_')));
    }

    /**
     * Convertir un nombre de atributo a snake_case.
     *
     * @param  string  $key
     * @return string
     */
    protected function toSnakeCase($key)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
    }

    /**
     * Obtener el valor de un atributo en camelCase.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getCamelCaseAttribute($key)
    {
        $snakeCaseKey = $this->toSnakeCase($key);
        return $this->$snakeCaseKey;
    }

    /**
     * Establecer el valor de un atributo en camelCase.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function setCamelCaseAttribute($key, $value)
    {
        $snakeCaseKey = $this->toSnakeCase($key);
        $this->$snakeCaseKey = $value;
    }
}
