<?php
declare(strict_types=1);

require_once __DIR__ . '/../User.php';
require_once __DIR__ . '/../UserRepository.php';
require_once __DIR__ . '/../auth/Authenticator.php';
require_once __DIR__ . '/RegistrationController.php';

$users = new UserRepository(Database::getInstance());
$controller = new RegistrationController($users, new Authenticator(Session::getInstance(), $users));

return [
    new Route('GET', '/register', [$controller, 'show']),
    new Route('POST', '/register', [$controller, 'register']),
];