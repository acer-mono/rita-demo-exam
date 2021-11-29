<?php
declare(strict_types=1);

/**
 * Класс аутентификации пользователей.
 */
final class Authenticator
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Осуществляет попытку аутентификации пользователя по логину и паролю.
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function login(string $login, string $password): bool
    {
        // TODO: проверять пользователя в базе данных

        $this->session->login($login);

        return true;
    }

    /**
     * Разлогинивает пользователя.
     */
    public function logout()
    {
        $this->session->logout();
    }
}
