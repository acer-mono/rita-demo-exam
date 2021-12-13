<?php
declare(strict_types=1);

require_once __DIR__ . '/FetchUserInfoQuery.php';

return [
    (new Route('GET', '/account', static function () {
        if (is_ajax_request()) {
            $data = (new FetchUserInfoQuery(Database::getInstance()))
                ->execute(Session::getInstance()->getUserId());

            return send_json($data);
        }

        require_once __DIR__ . '/view.php';
    }))->addBefore(check_is_logged_in()),
];