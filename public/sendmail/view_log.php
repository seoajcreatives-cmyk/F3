<?php
header("Content-Type: text/plain; charset=UTF-8");
$file = __DIR__ . '/debug_log.txt';
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo "No log file yet.";
}
