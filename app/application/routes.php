<?php
declare(strict_types=1);

require_once __DIR__ . '/ApplicationController.php';
require_once __DIR__ . '/ApplicationStatus.php';
require_once __DIR__ . '/FetchApplicationsQuery.php';
require_once __DIR__ . '/FetchLatestApplicationsQuery.php';

$controller = new ApplicationController(
    Session::getInstance(),
    new FetchLatestApplicationsQuery(Database::getInstance()),
    new FetchApplicationsQuery(Database::getInstance())
);

return [
    // Несколько последних заявок, запрашиваются AJAX-ом
    new Route('GET', '/applications/latest', [$controller, 'latest']),
    // Страница заявок
    (new Route('GET', '/applications', [$controller, 'list']))
        ->addBefore(check_is_logged_in()),
];
