<?php
declare(strict_types=1);

/**
 * Контроллер по работе с заявками.
 */
final class ApplicationController
{
    private $latestApplicationsQuery;
    private $fetchApplicationsQuery;
    private $session;

    public function __construct(
        FetchLatestApplicationsQuery $latestApplicationsQuery,
        FetchApplicationsQuery $fetchApplicationsQuery,
        Session $session
    ) {
        $this->latestApplicationsQuery = $latestApplicationsQuery;
        $this->fetchApplicationsQuery  = $fetchApplicationsQuery;
        $this->session = $session;
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
