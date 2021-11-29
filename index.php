<?php
declare(strict_types=1);

require_once __DIR__ . '/includes.php';

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
