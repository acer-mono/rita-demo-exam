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
     * Ищет пользователя по логину.
     *
     * @param string $login
     * @return User|null
     */
    public function findByLogin(string $login)
    {
        $stmt = $this->database
            ->getConnection()
            ->prepare('SELECT * FROM user WHERE login = :login');

        $stmt->execute([':login' => $login]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        return new User(
            $user['login'],
            $user['email'],
            $user['password'],
            $user['name'],
            json_decode($user['roles'], true),
            (int) $user['id']
        );
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

            return $this->database
                ->getConnection()
                ->prepare($sql)
                ->execute([
                    ':login' => $user->getLogin(),
                    ':email' => $user->getEmail(),
                    ':password' => $user->getPassword(),
                    ':name' => $user->getName(),
                    ':roles' => json_encode($user->getRoles())
                ]);
        } catch (PDOException $exception) {
            if ($exception->errorInfo[0] === '23000') {
                throw UserException::alreadyExists($user->getLogin());
            }

            throw UserException::fromPrevious($exception);
        }
    }
}
