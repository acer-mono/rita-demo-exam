<?php
declare(strict_types=1);

// Подключаем необходимые файлы
//
// require_once используется во избежание
// многократного подключения одного и того же файла
$files = [
    __DIR__ . '/framework/functions.php',
    __DIR__ . '/framework/Route.php',
    __DIR__ . '/framework/Router.php',
    __DIR__ . '/framework/Session.php',
    __DIR__ . '/framework/Database.php',
    __DIR__ . '/framework/FileUploader.php',
    __DIR__ . '/framework/FileUploaderException.php',
];

foreach ($files as $file) {
    require_once $file;
}
