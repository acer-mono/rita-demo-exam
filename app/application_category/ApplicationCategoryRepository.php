<?php
declare(strict_types=1);

/**
 * Репозиторий для управления категориями.
 */
final class ApplicationCategoryRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Возвращает категорию по её идентификатору.
     *
     * @param int $id
     * @return ApplicationCategory
     * @throws ApplicationCategoryNotFoundException В случае, если категория не найдена
     */
    public function getById(int $id): ApplicationCategory
    {
        $sql = 'select * from application_category where id = :id';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category === false) {
            throw new ApplicationCategoryNotFoundException(
                sprintf('Категория №%d не найдена', $id)
            );
        }

        return new ApplicationCategory($category['name'], (int) $category['id']);
    }

    /**
     * Удаляет категорию.
     *
     * @param int $id
     */
    public function removeById(int $id)
    {
        $sql = 'delete from application_category where id = :id';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Сохраняет категорию.
     *
     * @param ApplicationCategory $category
     * @return int Идентификатор категории
     */
    public function store(ApplicationCategory $category): int
    {
        return $category->getId() === null
            ? $this->insert($category)
            : $this->update($category);
    }

    private function insert(ApplicationCategory $category): int
    {
        $sql = 'insert into application_category (name) values (:name)';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':name', $category->getName());
        $stmt->execute();

        return $this->database->getLastInsertId();
    }

    private function update(ApplicationCategory $category): int
    {
        $sql = 'update application_category set name = :name where id = :id';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $category->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $category->getName());
        $stmt->execute();

        return $category->getId();
    }
}
