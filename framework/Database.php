<?php
declare(strict_types=1);

final class Database
{
    private static $instance;
    private $pdo;

    public static function createInstance(string $dsn, string $username, string $password): self
    {
        $pdo = new PDO($dsn, $username, $password);

        // Указываем PDO кидать исключения в случае ошибок
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::$instance = new self($pdo);

        return self::$instance;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new \LogicException('Сначала необходимо создать объект класса базы данных.');
        }

        return self::$instance;
    }

    private function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
