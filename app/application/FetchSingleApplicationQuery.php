<?php
declare(strict_types=1);

final class FetchSingleApplicationQuery
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function execute(int $id)
    {
        $sql =<<<SQL
select
    app.id as id,
    ac.name as categoryName,
    author.name as authorName,
    resolver.name as resolverName,
    app.author_id as authorId,
    app.resolver_id as resolverId,
    app.title as title,
    app.description as description,
    app.photo_before as photoBefore,
    app.photo_after as photoAfter,
    app.status as status,
    app.resolution as resolution,
    app.created_at as createdAt,
    app.updated_at as updatedAt
from application app
inner join application_category ac on app.category_id = ac.id
inner join user author on app.author_id = author.id
left join user resolver on app.resolver_id = resolver.id
where app.id = :id
SQL;
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($application === false) {
            return null;
        }

        return $application;
    }
}
