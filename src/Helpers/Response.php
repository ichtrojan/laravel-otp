<?php

/**
 * @param int $code
 * @param string $message
 * @param array $data
 * @return object
 */
if (!function_exists('abortJson')) {
    function abortJson(int $code = 401, string $message, $additional_data = []) : object
    {
        return jsonPlug($code, false, $message, $additional_data);
    }
}

/**
 * @param string $message
 * @param array $data
 * @return object
 */
if (!function_exists('sendJson')) {
    function sendJson(string $message, $additional_data = [], $code = 200) : object
    {
        return jsonPlug($code, true, $message, $additional_data);
    }
}

/**
 * @param int $code
 * @param bool $status
 * @param string $message
 * @param array $additional_data
 * @return object
 */
if (!function_exists('jsonPlug')) {
    function jsonPlug(int $code, bool $status, string $message, array $additional_data = []) : object
    {
        return response()->json(
            array_merge([
                'status'    => $status,
                'message'   => $message
            ], $additional_data), $code);
    }
}




