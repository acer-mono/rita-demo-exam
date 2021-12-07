<?php
declare(strict_types=1);

/**
 * Загрузчик файлов.
 */
final class FileUploader
{
    private static $instance;

    /**
     * @param string $uploadsDirectory Директория, где будут лежать файлы
     * @return FileUploader
     */
    public static function createInstance(string $uploadsDirectory): self
    {
        chmod($uploadsDirectory, 0777);
        self::$instance = new self($uploadsDirectory);
        return self::$instance;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new \RuntimeException('Необходимо инициализировать FileUploader');
        }

        return self::$instance;
    }

    private $uploadsDirectory;

    private function __construct(string $uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    /**
     * Перемещает загруженный файл в директорию, заданную при инициализации.
     *
     * @param string $tmpFilePath Путь до временного файла
     * @param string $fileName Название файла после перемещения
     *
     * @throws FileUploaderException
     */
    public function move(string $tmpFilePath, string $fileName)
    {
        if (!is_uploaded_file($tmpFilePath)) {
            throw FileUploaderException::notAnUploadedFile();
        }

        if (!move_uploaded_file($tmpFilePath, sprintf('%s/%s', $this->uploadsDirectory, $fileName))) {
            throw FileUploaderException::cannotMoveFile($tmpFilePath, $fileName);
        }
    }
}
