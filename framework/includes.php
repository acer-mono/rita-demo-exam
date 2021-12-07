<?php
declare(strict_types=1);

// Подключаем необходимые файлы
//
// require_once используется во избежание
// многократного подключения одного и того же файла
$files = [
    __DIR__ . '/functions.php',
    __DIR__ . '/Route.php',
    __DIR__ . '/Router.php',
    __DIR__ . '/Session.php',
    __DIR__ . '/Database.php',
    __DIR__ . '/FileUploader.php',
    __DIR__ . '/FileUploaderException.php',
];

foreach ($files as $file) {
    require_once $file;
}
