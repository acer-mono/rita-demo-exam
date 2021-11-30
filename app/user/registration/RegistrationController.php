<?php
declare(strict_types=1);

/**
 * Контроллер регистрации пользователей.
 */
final class RegistrationController
{
    private $users;
    private $authenticator;

    public function __construct(
        UserRepository $users,
        Authenticator $authenticator
    ) {
        $this->users = $users;
        $this->authenticator = $authenticator;
    }

    /**
     * Показывает форму регистрации.
     */
    public function show()
    {
        require_once __DIR__ . '/view.php';
    }

    /**
     * Регистрирует пользователя.
     *
     * @return callable|void
     */
    public function register()
    {
        $errors = self::validateInput($_POST);

        if (!empty($errors)) {
            require_once __DIR__ . '/view.php';
            return;
        }

        try {
            $this->users->add(self::createUser($_POST));
        } catch (UserException $exception) {
            $errors = [
                $exception->getMessage()
            ];

            require_once __DIR__ . '/view.php';
            return;
        }


        $this->authenticator->login($_POST['login'], $_POST['password']);

        return redirect('/account');
    }

    private static function validateInput(array $data): array
    {
        return [];
    }

    private static function createUser(array $data): User
    {
        return new User(
            $data['login'],
            $data['email'],
            $data['password'],
            $data['name'],
            ['user']
        );
    }
}
