<?php
declare(strict_types=1);

final class ApplicationException extends \Exception
{
    public static function notFound(int $id): self
    {
        return new self(sprintf('Заявка %d не найдена', $id));
    }

    public static function cannotReject(): self
    {
        return new self('Отклонять можно только новые заявки.');
    }

    public static function emptyResolution(): self
    {
        return new self('Не указана причина отклонения или сопроводительный текст к решению заявки.');
    }
}
