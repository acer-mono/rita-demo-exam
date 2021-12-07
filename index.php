<?php
declare(strict_types=1);

require_once __DIR__ . '/includes.php';

// Подключаемся к БД
$dbConfig = require_once __DIR__ . '/config/database.php';
Database::createInstance(
    $dbConfig['DB_DSN'],
    $dbConfig['DB_USER'],
    $dbConfig['DB_PASS']
);

// Стартуем пользовательскую сессию
Session::getInstance()->start();

/**
 * Добавляем маршруты в роутер.
 *
 * @var Router $router
 */
$router = require_once __DIR__ . '/routes.php';

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
