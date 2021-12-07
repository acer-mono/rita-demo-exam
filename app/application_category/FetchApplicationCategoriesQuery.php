<?php
declare(strict_types=1);

/**
 * Запрос на выборку категорий.
 */
final class FetchApplicationCategoriesQuery
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function execute()
    {
        return $this->database
            ->getConnection()
            ->query('select * from application_category')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
