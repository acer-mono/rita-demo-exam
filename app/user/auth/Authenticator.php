<?php
declare(strict_types=1);

/**
 * Класс аутентификации пользователей.
 */
final class Authenticator
{
    private $session;
    private $users;

    public function __construct(
        Session $session,
        UserRepository $users
    ) {
        $this->session = $session;
        $this->users = $users;
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
        if ($this->users->exists($login, $password)) {
            $this->session->login($login);
            return true;
        }

        return false;
    }

    /**
     * Разлогинивает пользователя.
     */
    public function logout()
    {
        $this->session->logout();
    }
}
