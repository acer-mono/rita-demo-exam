<?php
declare(strict_types=1);

require_once __DIR__ . '/Application.php';
require_once __DIR__ . '/ApplicationController.php';
require_once __DIR__ . '/ApplicationNotFoundException.php';
require_once __DIR__ . '/ApplicationRepository.php';
require_once __DIR__ . '/ApplicationStatus.php';
require_once __DIR__ . '/FetchApplicationsQuery.php';
require_once __DIR__ . '/FetchLatestApplicationsQuery.php';

$controller = new ApplicationController(
    Session::getInstance(),
    new FetchLatestApplicationsQuery(Database::getInstance()),
    new FetchApplicationsQuery(Database::getInstance())
);

$routes = [
    // Несколько последних заявок, запрашиваются AJAX-ом
    new Route('GET', '/applications/latest', [$controller, 'latest']),
    // Страница заявок
    (new Route('GET', '/applications', [$controller, 'list']))
        ->addBefore(check_is_logged_in()),
    //(new Route('POST', '/applications', [$controller, 'store']))
    //   ->addBefore(check_is_logged_in())
];

return array_merge(
    $routes,
    require_once __DIR__ . '/category/routes.php'
);
