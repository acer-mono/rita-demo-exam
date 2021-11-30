<?php
declare(strict_types=1);

require_once __DIR__ . '/LatestApplicationsQuery.php';

return [
    // Несколько последних заявок, запрашиваются AJAX-ом
    new Route('GET', '/applications/latest', static function () {
        $query = new LatestApplicationsQuery(Database::getInstance());
        return send_json($query->fetch(4));
    }),
];
