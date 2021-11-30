<?php
declare(strict_types=1);

/**
 * Запрос на выборку последних заявок.
 */
final class LatestApplicationsQuery
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function fetch(int $limit): array
    {
        $sql =<<<SQL
            SELECT app.id,
                   app.title,
                   app.description,
                   app.photo_before as photo,
                   ac.uid as categoryUid,
                   ac.name as categoryName,
                   author.login as authorLogin,
                   author.name as authorName
            FROM application app
            INNER JOIN application_category ac on app.category_id = ac.id
            INNER JOIN user author on app.author_id = author.id
            WHERE app.resolver_id IS NULL
            ORDER BY created_at DESC
            LIMIT :limit
SQL;

        $stmt = $this->database->getConnection()->query($sql);
        $stmt->bindValue(':limit', $limit);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
