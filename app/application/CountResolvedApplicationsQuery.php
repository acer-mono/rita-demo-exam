<?php
declare(strict_types=1);

/**
 * Запрос на подсчет обработанных заявок.
 */
final class CountResolvedApplicationsQuery
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function execute(): int
    {
        $sql =<<<SQL
select count(*)
from application
where resolver_id is not null
and status != :new
SQL;
        $stmt = $this->database
            ->getConnection()
            ->prepare($sql);

        $stmt->execute([
            ':new' => ApplicationStatus::NEW
        ]);

        return (int) $stmt->fetch(PDO::FETCH_COLUMN);
    }
}
