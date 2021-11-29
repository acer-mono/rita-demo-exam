<?php
declare(strict_types=1);

/**
 * Контроллер главной страницы сайта.
 */
final class IndexController
{
    public function __construct()
    {
    }

    public function __invoke()
    {
        require_once __DIR__ . '/view.php';
    }
}
