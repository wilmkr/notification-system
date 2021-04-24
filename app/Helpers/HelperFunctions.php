<?php

/**
 * Create the structure of responses sent back to clients.
 *
 * @param  int $code
 * @param  string $message
 * @param  boolean $success
 * @param  array $data
 * @return json
 */
function formatResponse($code = 500, $message = 'Internal Server Error', $success = false, $data = [])
{
    return response()->json([
        'success' => $success,
        'status_code' => $code,
        'message' => $message,
        'data' => $data
    ], $code);
}

/**
 * Extract the http status code from an exception object if available.
 *
 * @param  Exception $error
 * @return int
 */
function fetchErrorCode($error)
{
    if (method_exists($error, 'getStatusCode')) {
        return  $error->getStatusCode();
    }

    if (method_exists($error, 'getCode')) {
        return $error->getCode();
    }

    return 0;
}
