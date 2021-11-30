<?php
declare(strict_types=1);

final class UserException extends \Exception
{
    public static function alreadyExists(string $login): self
    {
        return new self(sprintf('Логин "%s" уже занят', $login));
    }

    public static function fromPrevious(\Exception $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
