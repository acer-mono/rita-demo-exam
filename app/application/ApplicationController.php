<?php
declare(strict_types=1);

/**
 * Контроллер по работе с заявками.
 */
final class ApplicationController
{
    private $session;
    private $latestApplicationsQuery;
    private $fetchApplicationsQuery;

    public function __construct(
        Session $session,
        FetchLatestApplicationsQuery $latestApplicationsQuery,
        FetchApplicationsQuery $fetchApplicationsQuery,
    ) {
        $this->session = $session;
        $this->latestApplicationsQuery = $latestApplicationsQuery;
        $this->fetchApplicationsQuery  = $fetchApplicationsQuery;
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
                $this->session->hasRole('admin')
            ));
        }

        require_once __DIR__ . '/list.php';
    }
}