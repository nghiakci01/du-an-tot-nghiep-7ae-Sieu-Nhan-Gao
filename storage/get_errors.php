<?php
$lines = file(__DIR__ . '/logs/laravel.log');
$errors = array_filter($lines, function($line) {
    return strpos($line, 'production.ERROR') !== false || strpos($line, 'local.ERROR') !== false;
});
echo implode(PHP_EOL, array_slice($errors, -10));
