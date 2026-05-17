<?php
// ./helpers/response.php
function jsonResponse($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function response($success, $message, $data = [], $meta = [])
{
    return [
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'meta' => $meta
    ];
}