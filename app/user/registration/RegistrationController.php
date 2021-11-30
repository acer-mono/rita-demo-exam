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
        $errors = [];

        if (empty($data['login'])) {
            $errors['login'] = 'Необходимо указать логин.';
        }

        if (empty($data['email']) || mb_strpos($data['email'], '@') === false) {
            $errors['email'] = 'Необходимо указать корректный адрес электронной почты.';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Необходимо указать пароль.';
        } elseif (mb_strlen($data['password']) < 6) {
            $errors['password'] = 'Пароль должен содержать не менее 6 символов.';
        }

        if (empty($data['name'])) {
            $errors['name'] = 'Укажите своё имя.';
        }

        return $errors;
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
