<?php
declare(strict_types=1);

/**
 * Загрузчик фотографий для заявок.
 */
final class PhotoUploader
{
    const MAX_SIZE = 10000000;
    const MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/bmp' => 'bmp',
        'image/x-ms-bmp' => 'bmp',
        'image/tif' => 'tiff'
    ];

    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * Загружает файл в директорию, указанную при инициализации {@link FileUploader}.
     *
     * @param array $file
     * @return string
     * @throws PhotoUploaderException
     */
    public function upload(array $file): string
    {
        $mimeType = mime_content_type($file['tmp_name']);

        $allowedMimeTypes = array_keys(self::MIME_TYPES);

        if ($mimeType === false) {
            throw PhotoUploaderException::unknownMimeType($allowedMimeTypes);
        }

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw PhotoUploaderException::invalidMimeType($mimeType, $allowedMimeTypes);
        }

        if ((int) $file['size'] > self::MAX_SIZE) {
            throw PhotoUploaderException::invalidFileSize($file['size'], self::MAX_SIZE);
        }

        $fileName = sprintf(
            '%s.%s',
            hash('sha256', random_bytes(16)),
            self::MIME_TYPES[$mimeType]
        );

        try {
            $this->fileUploader->move($file['tmp_name'], $fileName);
        } catch (FileUploaderException $exception) {
            throw PhotoUploaderException::fromPrevious($exception);
        }

        return '/' . $fileName;
    }
}
