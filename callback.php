<?php
$callbackJSONData = file_get_contents('php://input');
$logFile = 'callback_log.json';
$log = fopen($logFile, 'a');
fwrite($log, $callbackJSONData);
fclose($log);

?>