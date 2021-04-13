<?php

if ( !function_exists('getStatusId') ) {
    /**
     * @param string|array $status
     * @param string       $type
     *
     * @return \Illuminate\Support\Collection
     */
    function getStatusId($status = '*', $type = 'users'): \Illuminate\Support\Collection
    {
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
     * @param string       $type
     *
     * @return \Illuminate\Support\Collection
     */
    function getStatusName($status = '*', $type = 'users'): \Illuminate\Support\Collection
    {
        $statuses = (array) __("statuses.{$type}");
        $_statuses = (array) $status === '*' ? $statuses : [];
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
