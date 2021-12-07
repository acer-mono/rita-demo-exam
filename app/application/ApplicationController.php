<?php
declare(strict_types=1);

/**
 * Контроллер по работе с заявками.
 */
final class ApplicationController
{
    private $session;
    private $applications;
    private $latestApplicationsQuery;
    private $fetchApplicationsQuery;
    private $countResolvedApplicationsQuery;
    private $fetchSingleApplicationQuery;
    private $photoUploader;

    public function __construct(
        Session $session,
        ApplicationRepository $applications,
        FetchLatestApplicationsQuery $latestApplicationsQuery,
        FetchApplicationsQuery $fetchApplicationsQuery,
        CountResolvedApplicationsQuery $countResolvedApplicationsQuery,
        FetchSingleApplicationQuery $fetchSingleApplicationQuery,
        PhotoUploader $photoUploader
    ) {
        $this->session = $session;
        $this->applications = $applications;
        $this->latestApplicationsQuery = $latestApplicationsQuery;
        $this->fetchApplicationsQuery = $fetchApplicationsQuery;
        $this->countResolvedApplicationsQuery = $countResolvedApplicationsQuery;
        $this->fetchSingleApplicationQuery = $fetchSingleApplicationQuery;
        $this->photoUploader = $photoUploader;
    }

    /**
     * Подгружает несколько последних заявок.
     *
     * @return callable
     */
    public function latest(): callable
    {
        return send_json($this->latestApplicationsQuery->execute(4));
    }

    /**
     * Выводит список заявок в зависимости от роли пользователя.
     */
    public function list()
    {
        if (is_ajax_request()) {
            return send_json($this->fetchApplicationsQuery->execute(
                $this->session->getUserId(),
                $this->session->isAdmin()
            ));
        }

        require_once __DIR__ . '/list.php';
    }

    /**
     * Возвращает общее количество закрытых заявок.
     *
     * @return callable
     */
    public function total(): callable
    {
        return send_json([
            'total' => $this->countResolvedApplicationsQuery->execute()
        ]);
    }

    /**
     * Выводит информацию о заявке с указанным идентификатором.
     *
     * @param int $id
     */
    public function show($id)
    {
        $application = $this->fetchSingleApplicationQuery->execute((int) $id);

        if ($application === null || (int) $application['authorId'] !== $this->session->getUserId()) {
            return show_404();
        }

        require_once __DIR__ . '/show.php';
    }

    /**
     * Показывает страницу создания заявки.
     */
    public function create()
    {
        require_once __DIR__ . '/create.php';
    }

    /**
     * Сохраняет заявку.
     *
     * @return callable
     */
    public function store(): callable
    {
        $errors = self::validateCreationInput($_POST, $_FILES);

        if (count($errors)) {
            return send_json([
                'errors' => $errors
            ], 400);
        }

        try {
            $photoPath = $this->photoUploader->upload($_FILES['photo']);

            $application = Application::create(
                $this->session->getUserId(),
                (int) $_POST['categoryId'],
                $_POST['title'],
                $_POST['description'],
                $photoPath
            );

            $applicationId = $this->applications->store($application);

            return send_json([
                'applicationId' => $applicationId
            ], 201);
        } catch (Exception $exception) {
            return send_json([
                'errors' => [
                    $exception->getMessage()
                ]
            ], 500);
        }
    }

    private static function validateCreationInput(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['categoryId'])) {
            $errors['categoryId'] = 'Укажите категорию заявки.';
        }

        if (empty($data['title'])) {
            $errors['title'] = 'Укажите тему заявки.';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Опишите, в чём суть заявки.';
        }

        if (empty($files['photo'])) {
            $errors['photo'] = 'Необходимо загрузить фотографию.';
        }

        return $errors;
    }

    /**
     * Отклоняет заявку с указанным идентификатором.
     *
     * @param $id
     * @return callable
     */
    public function reject($id): callable
    {
        try {
            if (empty($_POST['resolution'])) {
                return send_json([
                    'errors' => [
                        'resolution' => 'Необходимо указать причину отклонения заявки.'
                    ]
                ], 400);
            }

            $application = $this->applications->getById((int) $id);

            $application->reject($this->session->getUserId(), $_POST['resolution']);

            $this->applications->store($application);

            return send_json([
                //
            ]);
        } catch (ApplicationException $exception) {
            return send_json([
                'errors' => [
                    $exception->getMessage()
                ]
            ], 400);
        }
    }

    /**
     * Разрешает заявку с указанным идентификатором.
     *
     * @param $id
     * @return callable
     */
    public function resolve($id): callable
    {
        try {
            $errors = self::validateResolutionInput($_POST, $_FILES);

            if (!empty($errors)) {
                return send_json([
                    'errors' => $errors
                ], 400);
            }

            $application = $this->applications->getById((int) $id);

            $photoPath = $this->photoUploader->upload($_FILES['photo']);

            $application->resolve(
                $this->session->getUserId(),
                $_POST['resolution'],
                $photoPath
            );

            $this->applications->store($application);

            return send_json([
                //
            ]);
        } catch (\Exception $exception) {
            return send_json([
                'errors' => [
                    $exception->getMessage()
                ]
            ], 400);
        }
    }

    private static function validateResolutionInput(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['resolution'])) {
            $errors['resolution'] = 'Необходимо указать описание решения.';
        }

        if (empty($files['photo'])) {
            $errors['photo'] = 'Необходимо загрузить фотографию.';
        }

        return $errors;
    }

    /**
     * Удаляет заявку по указанному идентифкатору.
     *
     * @param int $id
     * @return callable
     */
    public function delete($id): callable
    {
        try {
            $application = $this->applications->getById((int) $id);

            if ($application->getAuthorId() !== $this->session->getUserId()) {
                return send_json([
                    'error' => 'Заявка не найдена'
                ], 404);
            }

            $this->applications->remove($application);

            return send_json([
                //
            ]);
        } catch (ApplicationException $exception) {
            return send_json([
                'errors' => [
                    $exception->getMessage()
                ]
            ], 400);
        }
    }
}
