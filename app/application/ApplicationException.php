<?php
declare(strict_types=1);

final class ApplicationException extends \Exception
{
    public static function notFound(int $id): self
    {
        return new self(sprintf('Заявка %d не найдена', $id));
    }
}
