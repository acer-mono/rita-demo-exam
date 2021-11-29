<?php
declare(strict_types=1);

$router = new Router();

//
// Сначала добавляем глобальные обработчики
//
$router->addBefore(static function () {
    require_once __DIR__ . '/app/layout/header.php';
});

$router->addAfter(static function () {
    require_once __DIR__ . '/app/layout/footer.php';
});

$routes = [
    new Route('GET', '(.*)', static function () {
        header('HTTP/1.0 404 Not Found');
        require_once __DIR__ . '/app/pages/404.php';
    }),
];

// Добавляем маршруты в роутер
foreach ($routes as $route) {
    $router->addRoute($route);
}

// Возвращаем объект роутера, который будет использован в index.php
return $router;
