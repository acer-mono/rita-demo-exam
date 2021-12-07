<?php
declare(strict_types=1);

require_once __DIR__ . '/ApplicationCategory.php';
require_once __DIR__ . '/ApplicationCategoryController.php';
require_once __DIR__ . '/ApplicationCategoryNotFoundException.php';
require_once __DIR__ . '/ApplicationCategoryRepository.php';
require_once __DIR__ . '/FetchApplicationCategoriesQuery.php';

$controller = new ApplicationCategoryController(
    new FetchApplicationCategoriesQuery(Database::getInstance()),
    new ApplicationCategoryRepository(Database::getInstance())
);

return [
    (new Route('GET', '/categories', [$controller, 'list']))
        ->addBefore(check_is_admin()),
    (new Route('POST', '/categories', [$controller, 'create']))
        ->addBefore(check_is_admin()),
    (new Route('POST', '/categories/(\d+)', [$controller, 'update']))
        ->addBefore(check_is_admin()),
    (new Route('POST', '/categories/(\d+)/delete', [$controller, 'delete']))
        ->addBefore(check_is_admin()),
];