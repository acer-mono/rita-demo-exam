<?php
declare(strict_types=1);

final class FileUploaderException extends \Exception
{
    public static function notAnUploadedFile(): self
    {
        return new self('Файл не был загружен по HTTP');
    }

    public static function cannotMoveFile(string $from, string $fileName): self
    {
        return new self(sprintf(
            'Не удалось переместить файл %s с именем %s...',
            $from,
            $fileName
        ));
    }
}
