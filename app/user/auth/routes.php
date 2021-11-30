<?php
declare(strict_types=1);

require_once __DIR__ . '/AuthController.php';

$controller = new AuthController(new Authenticator(Session::getInstance()));

return [
    new Route(['GET', 'POST'], '/login', [$controller, 'login']),
    new Route('GET', '/logout', [$controller, 'logout']),
];