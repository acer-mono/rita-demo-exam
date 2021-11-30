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

/**
 * Проверяет, является ли текущий запрос AJAX-запросом.
 *
 * @return bool
 */
function is_ajax_request(): bool
{
    $requestedWith = (
        isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    );

    $accept = (
        isset($_SERVER['HTTP_ACCEPT'])
        && $_SERVER['HTTP_ACCEPT'] === 'application/json'
    );

    return $requestedWith || $accept;
}

/**
 * Отправляет данные в JSON-формате.
 *
 * @param mixed $data
 * @return callable
 */
function send_json($data): callable
{
    return static function () use ($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    };
}
