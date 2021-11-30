<?php
declare(strict_types=1);

/**
 * Репозиторий пользователей для удобной работы с ними.
 */
final class UserRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Проверяет, существует ли пользователь с указанным логином и паролем.
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function exists(string $login, string $password): bool
    {
        $stmt = $this->database
            ->getConnection()
            ->prepare('SELECT * FROM user WHERE login = :login');

        $stmt->bindValue(':login', $login);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return false;
        }

        return password_verify($password, $user['password']);
    }
}
