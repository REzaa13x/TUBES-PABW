<?php
$ports = [3306, 3307, 3308, 8889, 33060];
foreach ($ports as $port) {
    $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.5);
    if (is_resource($connection)) {
        echo "Port $port is OPEN\n";
        fclose($connection);
    } else {
        // echo "Port $port is CLOSED\n";
    }
}
