<?php
declare(strict_types=1);

/**
 * Репозиторий для управления заявками.
 */
final class ApplicationRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Возвращает заявку по её идентификатору.
     *
     * @param int $id
     * @return Application
     * @throws ApplicationNotFoundException В случае если заявка не найдена
     */
    public function getById(int $id): Application
    {
        $sql = 'select * from application where id = :id';

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($application === false) {
            throw new ApplicationNotFoundException(sprintf('Заявка %d не найдена', $id));
        }

        return Application::fromStorage($application);
    }

    /**
     * Сохраняет заявку в БД.
     *
     * @param Application $application
     * @return int Идентификатор заявки
     */
    public function store(Application $application): int
    {
        return $application->getId() === null
            ? $this->insert($application)
            : $this->update($application);
    }

    private function insert(Application $application): int
    {
        $sql =<<<SQL
insert into application
    (author_id, resolver_id, category_id, title, description, photo_before, photo_after,
     status, resolution, created_at, updated_at)
values
    (:authorId, :resolverId, :categoryId, :title, :description, :photoBefore, :photoAfter,
     :status, :resolution, :createdAt, :updatedAt)
SQL;
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':authorId', $application->getAuthorId());
        $stmt->bindValue(':resolverId', $application->getResolverId());
        $stmt->bindValue(':categoryId', $application->getCategoryId());
        $stmt->bindValue(':title', $application->getTitle());
        $stmt->bindValue(':description', $application->getDescription());
        $stmt->bindValue(':photoBefore', $application->getPhotoBefore());
        $stmt->bindValue(':photoAfter', $application->getPhotoAfter());
        $stmt->bindValue(':status', $application->getStatus());
        $stmt->bindValue(':resolution', $application->getResolution());
        $stmt->bindValue(':createdAt', $application->getCreatedAt()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':updatedAt', $application->getUpdatedAt()->format('Y-m-d H:i:s'));
        $stmt->execute();

        return $this->database->getLastInsertId();
    }

    private function update(Application $application): int
    {
        $sql =<<<SQL
update application
set
    author_id = :authorId,
    resolver_id = :resolverId,
    category_id = :categoryId,
    title = :title,
    description = :description,
    photo_before = :photoBefore,
    photo_after = :photoAfter,
    status = :status,
    resolution = :resolution,
    created_at = :createdAt,
    updated_at = :updatedAt
where id = :id
SQL;

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $application->getId());
        $stmt->bindValue(':authorId', $application->getAuthorId());
        $stmt->bindValue(':resolverId', $application->getResolverId());
        $stmt->bindValue(':categoryId', $application->getCategoryId());
        $stmt->bindValue(':title', $application->getTitle());
        $stmt->bindValue(':description', $application->getDescription());
        $stmt->bindValue(':photoBefore', $application->getPhotoBefore());
        $stmt->bindValue(':photoAfter', $application->getPhotoAfter());
        $stmt->bindValue(':status', $application->getStatus());
        $stmt->bindValue(':resolution', $application->getResolution());
        $stmt->bindValue(':createdAt', $application->getCreatedAt()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':updatedAt', $application->getUpdatedAt()->format('Y-m-d H:i:s'));
        $stmt->execute();

        return $application->getId();
    }

    /**
     * Удаляет заявку.
     *
     * @param Application $application
     */
    public function remove(Application $application)
    {
        $sql = 'delete from application where id = :id';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $application->getId(), PDO::PARAM_INT);
        $stmt->execute();
    }
}
