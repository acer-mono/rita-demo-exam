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
            select app.id,
                   app.title,
                   app.description,
                   app.photo_before as photo,
                   ac.uid as categoryUid,
                   ac.name as categoryName,
                   author.login as authorLogin,
                   author.name as authorName
            from application app
            inner join application_category ac on app.category_id = ac.id
            inner join user author on app.author_id = author.id
            where app.resolver_id is null
            order by created_at desc
            limit :limit
SQL;

        $stmt = $this->database->getConnection()->query($sql);
        $stmt->bindValue(':limit', $limit);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
