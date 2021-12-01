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

/**
 * Проверяет залогинен ли пользователь.
 *
 * @return callable
 */
function check_is_logged_in(): callable
{
    if (Session::getInstance()->isLoggedIn()) {
        return static function () {
            // пользователь залогинен, ничего не делаем
        };
    }

    header('HTTP/1.0 401 Unauthorized');

    if (is_ajax_request()) {
        return send_json([
            'error' => 'Ошибка доступа. Пожалуйста, войдите в систему.'
        ]);
    }

    return redirect('/login');
}

/**
 * Проверяет, является ли пользователь админом.
 *
 * @return callable
 */
function check_is_admin(): callable
{
    $session = Session::getInstance();

    if ($session->isLoggedIn() && $session->hasRole('admin')) {
        return static function () {
            // пользователь админ, ничего не делаем
        };
    }

    header('HTTP/1.0 403 Forbidden');

    if (is_ajax_request()) {
        return send_json([
            'error' => 'Доступ в этот раздел ограничен.'
        ]);
    }

    return static function () {
        require_once __DIR__ . '/../app/pages/403.php';

        // Отменяем выполнение всех остальных обработчиков маршрута
        return false;
    };
}
