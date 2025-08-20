<?php

require_once __DIR__ . '/vendor/autoload.php';

$url = 'http://localhost:8888/api/reception/notifications/unread-count';
$token = '17|NMInjcKgPwLFhqN7jUq0FFrSmXAHP1uJlCSCFWbi6c362924';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
            'Content-Type: application/json'
        ]
    ]
]);

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "Error: Unable to fetch data\n";
    echo "HTTP Response Headers:\n";
    var_dump($http_response_header);
} else {
    echo "Response: " . $response . "\n";
}
