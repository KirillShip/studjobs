<?php
function handle_error($http_code, $developer_message, $user_message = '', $details = [])
{
    http_response_code($http_code);

    $response = [
        'error' => true,
        'http_code' => $http_code,
        'developer_message' => $developer_message,
        'user_message' => $user_message,
        'details' => $details,
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}