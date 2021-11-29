<?php
declare(strict_types=1);

/**
 * Возвращает функцию, которая отравляет заголовок перенаправления по указанному пути.
 *
 * @param string $path
 * @return callable
 */
function redirect(string $path): callable
{
    return static function () use ($path) {
        header(sprintf('Location: %s', $path));
    };
}