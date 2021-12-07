<?php
declare(strict_types=1);

final class PhotoUploaderException extends \Exception
{
    public static function unknownMimeType(array $expected): self
    {
        return new self(sprintf(
            'Не удалось определить тип файла. Ожидается один из перечисленных: %s',
            implode(', ', $expected)
        ));
    }

    public static function invalidMimeType(string $actual, array $expected): self
    {
        return new self(sprintf(
            'Некорректный тип файла: %s. Ожидается один из перечисленных: %s',
            $actual,
            implode(', ', $expected)
        ));
    }

    public static function invalidFileSize(int $actual, int $expected): self
    {
        return new self(sprintf(
            ''
        ));
    }

    public static function fromPrevious(\Exception $exception): self
    {
        return new self($exception->getMessage(), 0, $exception);
    }
}
