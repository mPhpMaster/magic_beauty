<?php

if ( !function_exists('when') ) {
    /**
     * @param Closure|mixed|null $cond
     * @param Closure|mixed|null $true
     * @param Closure|mixed|null $false
     *
     * @return mixed
     */
    function when($cond, $true, $false = null)
    {
        return value($cond) ? value($true) : value($false);
    }
}

if ( !function_exists('isClosure') ) {
    /**
     * @param mixed $var
     *
     * @return bool
     */
    function isClosure($var): bool
    {
        return $var instanceof Closure;
    }
}

if ( !function_exists('apiJsonResource') ) {
    /**
     * Create a new json resource instance.
     *
     * @param mixed  $resource
     * @param string $class
     * @param bool   $success
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Throwable
     */
    function apiJsonResource($resource, $class = null, $success = true): \Illuminate\Http\Resources\Json\JsonResource
    {
        $class = $class ?? \Illuminate\Http\Resources\Json\JsonResource::class;
        throw_if(!class_exists($class), new Exception("Class {$class} not exists!"));

        $instance = new $class($resource);
        return $instance->additional([
            "success" => $success,
        ]);
    }
}

if ( !function_exists('parseMobile') ) {
    /**
     * @param $mobile
     *
     * @return string|int|null
     */
    function parseMobile($mobile)
    {
        $mobile = preg_replace("[\D]", "", $mobile);
        if(starts_with($mobile, "00")) {
            $mobile = substr($mobile, 2);
        }

        return $mobile;
    }
}
