<?php
declare(strict_types=1);

final class AuthController
{
    private $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/view.php';
            return;
        }

        if (empty($_POST['login'])) {
            $errors[] = 'Заполните поле логина.';
        }

        if (empty($_POST['password'])) {
            $errors[] = 'Заполните поле пароля.';
        }

        if (isset($errors)) {
            require_once __DIR__ . '/view.php';
            return;
        }

        if ($this->authenticator->login($_POST['login'], $_POST['password'])) {
            return redirect('/account');
        }

        $errors = [
            'Не удалось осуществить вход в личный кабинет.'
        ];

        require_once __DIR__ . '/view.php';
    }

    public function logout(): callable
    {
        $this->authenticator->logout();

        return redirect('/');
    }
}
