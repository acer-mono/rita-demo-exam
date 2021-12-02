<?php
declare(strict_types=1);

final class FetchApplicationsQuery
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Возвращает массив заявок для пользователя с указанным идентификатором.
     *
     * @param int $userId Идентификатор пользователя
     * @param bool $isAdmin Если пользователь - админ, в выборку попадут заявки со статусом "Новая"
     * @return array
     */
    public function execute(int $userId, bool $isAdmin): array
    {
        $params = [
            [':userId', $userId, PDO::PARAM_INT]
        ];

        $sql =<<<SQL
            select app.id,
                   app.title,
                   app.description,
                   app.photo_before as photoBefore,
                   app.photo_after as photoAfter,
                   app.status as status,
                   app.created_at as createdAt,
                   app.updated_at as updatedAt,
                   ac.name as categoryName,
                   author.login as authorLogin,
                   author.name as authorName,
                   resolver.login as resolverLogin,
                   resolver.name as resolverName
            from application app
            inner join application_category ac on app.category_id = ac.id
            inner join user author on app.author_id = author.id
            left join user resolver on app.resolver_id = resolver.id
            where 
SQL;

        if ($isAdmin) {
            $sql .=<<<SQL
                -- админу показываем новые заявки и заявки, закрытые или отклоненные им 
                (app.status = :statusNew and app.resolver_id is null)
                or (app.status IN (:statusResolved, :statusRejected) and app.resolver_id = :userId)
                order by app.created_at desc, app.updated_at desc
SQL;
            $params[] = [':statusNew', ApplicationStatus::NEW, PDO::PARAM_INT];
            $params[] = [':statusResolved', ApplicationStatus::RESOLVED, PDO::PARAM_INT];
            $params[] = [':statusRejected', ApplicationStatus::REJECTED, PDO::PARAM_INT];
        } else {
            $sql .=<<<SQL
                app.author_id = :userId
                -- для обычного пользователя сортируем по дате обновления,
                -- чтобы ему было лучше видно прогресс по заявкам
                order by updated_at desc
SQL;
        }

        $stmt = $this->database->getConnection()->prepare($sql);

        foreach ($params as $param) {
            $stmt->bindValue($param[0], $param[1], $param[2]);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
