<?php
declare(strict_types=1);

$dbConfig = require_once __DIR__ . '/database.config.php';

Database::createInstance(
    $dbConfig['DB_DSN'],
    $dbConfig['DB_USER'],
    $dbConfig['DB_PASS']
);
