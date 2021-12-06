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

    public function __construct(
        Session $session,
        ApplicationRepository $applications,
        FetchLatestApplicationsQuery $latestApplicationsQuery,
        FetchApplicationsQuery $fetchApplicationsQuery,
        CountResolvedApplicationsQuery $countResolvedApplicationsQuery,
        FetchSingleApplicationQuery $fetchSingleApplicationQuery
    ) {
        $this->session = $session;
        $this->applications = $applications;
        $this->latestApplicationsQuery = $latestApplicationsQuery;
        $this->fetchApplicationsQuery = $fetchApplicationsQuery;
        $this->countResolvedApplicationsQuery = $countResolvedApplicationsQuery;
        $this->fetchSingleApplicationQuery = $fetchSingleApplicationQuery;
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
     * Удаляет заявку по указанному идентифкатору.
     *
     * @param int $id
     * @return callable
     */
    public function delete($id): callable
    {
        try {
            $application = $this->applications->getById((int) $id);

            if (!$application->isNew()) {
                return send_json([
                    'error' => 'Удалить можно только новую заявку.'
                ], 400);
            }

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
                'error' => $exception->getMessage()
            ], 404);
        }
    }
}
