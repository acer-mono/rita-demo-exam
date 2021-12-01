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
        $user = $this->users->findByLogin($login);

        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $this->session->store([
            'login' => $user->getLogin(),
            'roles' => $user->getRoles()
        ]);

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
