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

    /**
     * Добавляет пользователя в БД.
     *
     * @param User $user
     * @return bool
     * @throws UserException
     */
    public function add(User $user): bool
    {
        try {
            $sql =<<<SQL
    INSERT INTO user (login, email, password, name, roles)
    VALUES (:login, :email, :password, :name, :roles)
SQL;

            $stmt = $this->database
                ->getConnection()
                ->prepare($sql);

            $stmt->bindValue(':login', $user->getLogin());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':password', $user->getPassword());
            $stmt->bindValue(':name', $user->getName());
            $stmt->bindValue(':roles', json_encode($user->getRoles()));

            return $stmt->execute();
        } catch (PDOException $exception) {
            if ($exception->errorInfo[0] === '23000') {
                throw UserException::alreadyExists($user->getLogin());
            }

            throw UserException::fromPrevious($exception);
        }
    }
}
