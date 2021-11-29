<?php
declare(strict_types=1);

require_once __DIR__ . '/IndexController.php';

return [
    new Route('GET', '/', new IndexController())
];