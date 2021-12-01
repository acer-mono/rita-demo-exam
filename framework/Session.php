<?php
declare(strict_types=1);

/**
 * Удобная обертка над суперглобальной переменной $_SESSION,
 * используется для обработки сессионных данных.
 */
final class Session
{
    private static $instance;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * Стартует сессию.
     */
    public function start()
    {
        session_name('ritka_auth');
        session_start();
    }

    /**
     * Указывает, залогинен ли пользователь.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['login']);
    }

    public function login(string $login)
    {
        $_SESSION['login'] = $login;
    }

    /**
     * Удаляет информацию о пользователе из сессии.
     */
    public function logout()
    {
        $_SESSION = [];

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        session_destroy();
    }
}
