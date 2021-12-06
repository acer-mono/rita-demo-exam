<?php
declare(strict_types=1);

require_once __DIR__ . '/Application.php';
require_once __DIR__ . '/ApplicationController.php';
require_once __DIR__ . '/ApplicationException.php';
require_once __DIR__ . '/ApplicationRepository.php';
require_once __DIR__ . '/ApplicationStatus.php';
require_once __DIR__ . '/CountResolvedApplicationsQuery.php';
require_once __DIR__ . '/FetchApplicationsQuery.php';
require_once __DIR__ . '/FetchSingleApplicationQuery.php';
require_once __DIR__ . '/FetchLatestApplicationsQuery.php';

$database = Database::getInstance();
$controller = new ApplicationController(
    Session::getInstance(),
    new ApplicationRepository($database),
    new FetchLatestApplicationsQuery($database),
    new FetchApplicationsQuery($database),
    new CountResolvedApplicationsQuery($database),
    new FetchSingleApplicationQuery($database)
);

$routes = [
    // Несколько последних заявок, запрашиваются AJAX-ом
    new Route('GET', '/applications/latest', [$controller, 'latest']),
    // Страница заявок
    (new Route('GET', '/applications', [$controller, 'list']))
        ->addBefore(check_is_logged_in()),
    (new Route('GET', '/applications/total', [$controller, 'total'])),
    (new Route('GET', '/applications/(\d+)', [$controller, 'show']))
        ->addBefore(check_is_logged_in()),
    (new Route('POST', '/applications/(\d+)/delete', [$controller, 'show']))
        ->addBefore(check_is_logged_in())
        ->addBefore(check_is_not_admin()),
    //(new Route('POST', '/applications', [$controller, 'store']))
    //   ->addBefore(check_is_logged_in())
];

return array_merge(
    $routes,
    require_once __DIR__ . '/category/routes.php'
);
