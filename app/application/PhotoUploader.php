<?php
declare(strict_types=1);

/**
 * Загрузчик фотографий для заявок.
 */
final class PhotoUploader
{
    const MAX_SIZE = 10000000;
    const MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/bmp',
        'image/x-ms-bmp',
        'image/tif'
    ];

    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * Загружает фотографию "до".
     *
     * @param array $file
     * @param int $applicationId
     * @return string
     * @throws PhotoUploaderException
     */
    public function uploadPhotoBefore(array $file, int $applicationId): string
    {
        return $this->upload($file, $applicationId, 'before');
    }

    /**
     * Загружает фотографию "после".
     *
     * @param array $file
     * @param int $applicationId
     * @return string
     * @throws PhotoUploaderException
     */
    public function uploadPhotoAfter(array $file, int $applicationId): string
    {
        return $this->upload($file, $applicationId, 'after');
    }

    /**
     * @param array $file
     * @param int $applicationId
     * @param string $beforeOrAfter
     * @return string
     * @throws PhotoUploaderException
     */
    private function upload(array $file, int $applicationId, string $beforeOrAfter): string
    {
        $mimeType = mime_content_type($file['tmp_name']);

        if ($mimeType === false) {
            throw PhotoUploaderException::unknownMimeType(self::MIME_TYPES);
        }

        if (!in_array($mimeType, self::MIME_TYPES, true)) {
            throw PhotoUploaderException::invalidMimeType($mimeType, self::MIME_TYPES);
        }

        if ((int) $file['size'] > self::MAX_SIZE) {
            throw PhotoUploaderException::invalidFileSize($file['size'], self::MAX_SIZE);
        }

        $fileName = sprintf(
            'application_%d_%s.jpg',
            $applicationId,
            $beforeOrAfter
        );

        try {
            $this->fileUploader->move($file['tmp_name'], $fileName);
        } catch (FileUploaderException $exception) {
            throw PhotoUploaderException::fromPrevious($exception);
        }

        return $fileName;
    }
}
