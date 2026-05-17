<?php

$path = __DIR__ . '/../storage/cache/';

foreach (glob($path . "*.json") as $file) {
    unlink($file);
}

echo "Cache cleared successfully\n";