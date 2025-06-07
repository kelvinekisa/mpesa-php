<?php
$callbackJSONData = file_get_contents('php://input');
$logFile = 'callback_log.json';

$logEntry = [
    'timestamp' => date('Y-m-d H:i:s'),
    'data' => json_decode($callbackJSONData, true)
];

file_put_contents($logFile, json_encode($logEntry, JSON_PRETTY_PRINT) . ",\n", FILE_APPEND);
?>
