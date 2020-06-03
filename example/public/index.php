<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

(new Germix\Api\Kernel())->run([
    'commands_file' => getenv('COMMANDS_FILE')
]);
