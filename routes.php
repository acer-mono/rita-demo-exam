<?php
declare(strict_types=1);

$router = new Router();

$router->addBefore(static function () {
    $lines = file(__DIR__ . '/.env');

    $dsn = '';
    $username = '';
    $password = '';

    foreach ($lines as $line) {
        if (strpos($line, 'DB_DSN') === 0) {
            $dsn = trim(substr($line, 7));
        }

        if (strpos($line, 'DB_USER') === 0) {
            $username = trim(substr($line, 8));
        }

        if (strpos($line, 'DB_PASS') === 0) {
            $password = trim(substr($line, 8));
        }
    }

    Database::createInstance($dsn, $username, $password);
});

// Добавляем глобальные обработчики только в том случае,
// если нам отправили обычный, а не AJAX-запрос
if (!is_ajax_request()) {
    $router->addBefore(static function () {
        require_once __DIR__ . '/app/layout/header.php';
    });

    $router->addAfter(static function () {
        require_once __DIR__ . '/app/layout/footer.php';
    });
}

/**
 * Объединяет маршруты из разных модулей в один массив.
 *
 * Маршруты подгружаются из файлов соответствующих модулей.
 * Каждый файл routes.php должен возвращать массив объектов класса {@link Route}.
 *
 * @var Route $routes
 */
$routes = array_merge(
    require_once __DIR__ . '/app/index/routes.php',
    require_once __DIR__ . '/app/auth/routes.php',
    [
        new Route('GET', '(.*)', static function () {
            header('HTTP/1.0 404 Not Found');
            require_once __DIR__ . '/app/pages/404.php';
        }),
    ]
);

// Добавляем маршруты в роутер
foreach ($routes as $route) {
    $router->addRoute($route);
}

// Возвращаем объект роутера, который будет использован в index.php
return $router;
