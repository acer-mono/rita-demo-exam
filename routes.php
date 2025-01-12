<?php
declare(strict_types=1);

$router = new Router();

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
    require_once __DIR__ . '/app/user/account/routes.php',
    require_once __DIR__ . '/app/user/auth/routes.php',
    require_once __DIR__ . '/app/user/registration/routes.php',
    require_once __DIR__ . '/app/application/routes.php',
    require_once __DIR__ . '/app/application_category/routes.php',
    [
        new Route('ANY', '(.*)', show_404()),
    ]
);

// Добавляем маршруты в роутер
foreach ($routes as $route) {
    $router->addRoute($route);
}

// Возвращаем объект роутера, который будет использован в index.php
return $router;
