<?php
declare(strict_types=1);

final class Application
{
    private $id;
    private $authorId;
    private $resolverId;
    private $categoryId;
    private $title;
    private $description;
    private $photoBefore;
    private $photoAfter;
    private $status;
    private $resolution;
    private $createdAt;
    private $updatedAt;

    /**
     * Создает новую заявку.
     *
     * @param int $authorId
     * @param int $categoryId
     * @param string $title
     * @param string $description
     * @param string $photoBefore
     * @return Application
     */
    public static function create(
        int $authorId,
        int $categoryId,
        string $title,
        string $description,
        string $photoBefore
    ): self {
        $self = new self();
        $self->authorId = $authorId;
        $self->categoryId = $categoryId;
        $self->title = $title;
        $self->description = $description;
        $self->photoBefore = $photoBefore;
        $self->status = ApplicationStatus::NEW;
        $self->createdAt = new DateTimeImmutable();
        $self->updatedAt = new DateTimeImmutable();

        return $self;
    }

    /**
     * @param array $data
     * @return static
     * @throws Exception
     * @internal Костыль, используется только для восстановления сущности из БД.
     */
    public static function fromStorage(array $data): self
    {
        $self = new self();
        $self->id = (int) $data['id'];
        $self->authorId = (int) $data['author_id'];
        $self->resolverId = $data['resolver_id'] === null ? null : (int) $data['resolver_id'];
        $self->categoryId = (int) $data['category_id'];
        $self->title = $data['title'];
        $self->description = $data['description'];
        $self->photoBefore = $data['photo_before'];
        $self->photoAfter = $data['photo_after'];
        $self->status = (int) $data['status'];
        $self->resolution = $data['resolution'];
        $self->createdAt = new DateTimeImmutable($data['created_at']);
        $self->updatedAt = new DateTimeImmutable($data['updated_at']);

        return $self;
    }

    private function __construct()
    {
    }

    /**
     * Отклоняет заявку.
     *
     * @param int $userId Идентификатор пользователя, отклонившего заявку
     * @param string $resolution Причина отклонения заявки
     * @throws ApplicationException
     */
    public function reject(int $userId, string $resolution)
    {
        if (!$this->isNew()) {
            throw ApplicationException::cannotReject();
        }

        if (empty($resolution)) {
            throw ApplicationException::emptyResolution();
        }

        $this->resolverId = $userId;
        $this->resolution = $resolution;
        $this->status = ApplicationStatus::REJECTED;
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return int|null
     */
    public function getResolverId()
    {
        return $this->resolverId;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPhotoBefore(): string
    {
        return $this->photoBefore;
    }

    /**
     * @return string|null
     */
    public function getPhotoAfter()
    {
        return $this->photoAfter;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    public function isNew(): bool
    {
        return $this->status === ApplicationStatus::NEW;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
