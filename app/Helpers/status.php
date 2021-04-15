<?php

if ( !function_exists('getStatusId') ) {
    /**
     * @param string|array $status
     * @param string|null       $type
     *
     * @return \Illuminate\Support\Collection
     */
    function getStatusId($status = '*', $type = null): \Illuminate\Support\Collection
    {
        try {
            if ( is_null($type) ) {
                $class = data_get(last(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)), 'class');
                if ( class_exists($class) && defined("{$class}::STATUS_TYPE") ) {
                    $type = constant("{$class}::STATUS_TYPE");
                }
            }
        } catch (Exception $exception) {

        }
        $type = $type ?: 'global';
        $statuses = __("statuses.{$type}");
        $_statuses = $status === '*' ? array_keys($statuses) : [];
        if ( $status !== '*' ) {
            foreach ((array)$status as $name) {
                if ( isset($statuses[ $name ]) ) {
                    $_statuses[] = $name;
                }
            }
        }

        return collect($_statuses);
    }
}

if ( !function_exists('getStatusName') ) {
    /**
     * @param string|array $status
     * @param string|null       $type
     *
     * @return \Illuminate\Support\Collection
     */
    function getStatusName($status = '*', $type = null): \Illuminate\Support\Collection
    {
        try {
            if ( is_null($type) ) {
                $class = data_get(last(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)), 'class');
                if ( class_exists($class) && defined("{$class}::STATUS_TYPE") ) {
                    $type = constant("{$class}::STATUS_TYPE");
                }
            }
        } catch (Exception $exception) {

        }
        $type = $type ?: 'global';

        $statuses = (array)__("statuses.{$type}");
        $_statuses = (array)$status === '*' ? $statuses : [];
        if ( $status !== '*' ) {
            foreach ((array)$status as $name) {
                if ( isset($statuses[ $name ]) ) {
                    $_statuses[] = $statuses[ $name ];
                }
            }
        }

        return collect($_statuses);
    }
}
