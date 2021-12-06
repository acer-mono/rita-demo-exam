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
     * Удаляет заявку по её идентификатору, если она новая.
     *
     * @param int $id
     */
    public function removeById(int $id)
    {
        $sql = 'delete from application where id = :id and status = :status';
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status', ApplicationStatus::NEW);
        $stmt->execute();
    }
}
