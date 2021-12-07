<?php
declare(strict_types=1);

final class FetchUserInfoQuery
{
    /**
     * @var Database
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function execute(int $userId)
    {
        $sql = 'select login, email, name from user where id = :id';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
