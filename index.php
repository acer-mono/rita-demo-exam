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
    __DIR__ . '/framework/Session.php'
];

foreach ($files as $file) {
    require_once $file;
}

/**
 * Добавляем маршруты в роутер.
 *
 * @var Router $router
 */
$router = require_once __DIR__ . '/routes.php';

// Стартуем пользовательскую сессию
Session::getInstance()->start();

/**
 * Пытаемся обработать текущий запрос к серверу.
 *
 * $_SERVER['REQUEST_URI'] содержит относительный путь (без схемы и хоста),
 * роутер попытается найти маршрут соответствующий этому пути.
 *
 * Если маршрут найден, то по цепочке будут вызваны обработчики этого маршрута,
 * заданные с помощью класса {@link Route}.
 */
$router->handle($_SERVER['REQUEST_URI']);
