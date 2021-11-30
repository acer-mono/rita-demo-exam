<?php
declare(strict_types=1);

final class Database
{
    private static $instance;
    private $pdo;

    public static function createInstance(string $dsn, string $username, string $password): self
    {
        self::$instance = new self(new PDO($dsn, $username, $password));

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
