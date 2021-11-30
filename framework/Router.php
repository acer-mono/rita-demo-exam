<?php
declare(strict_types=1);

/**
 * Маршрутизатор, подбирающий обработчик для указанного пути из запроса к серверу.
 */
final class Router
{
    /**
     * @var Route[]
     */
    private $routes = [];
    private $before = [];
    private $after = [];

    public function __construct()
    {
    }

    /**
     * Добавляет действие, которое будет выполнено еще до поиска подходящего маршрута.
     *
     * @param callable $action
     * @return $this
     */
    public function addBefore(callable $action): self
    {
        $this->before[] = $action;
        return $this;
    }

    /**
     * Добавляет действие, которое будет выполнено в самом конце, после все обработчиков маршрута.
     *
     * @param callable $action
     * @return $this
     */
    public function addAfter(callable $action): self
    {
        $this->after[] = $action;
        return $this;
    }

    /**
     * Добавляет маршрут в коллекцию роутера.
     *
     * @param Route $route
     * @return Route
     */
    public function addRoute(Route $route): Route
    {
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Пытается найти маршрут для указанного пути
     * и вызвать все имеющиеся обработчики, если таковые есть.
     *
     * @param string $path
     */
    public function handle(string $path)
    {
        // Выполняем глобальные обработчики запроса
        // вне зависимости от того, был ли найден подходящий маршрут
        foreach ($this->before as $action) {
            $action();
        }

        // Сначала ищем подходящий маршрут, получаем функцию-обработчик и выполняем ее
        $handler = $this->getHandler($path);
        $result = $handler();

        // Выполняем глобальные обработчики запроса
        // вне зависимости от того, был ли найден подходящий маршрут
        foreach ($this->after as $action) {
            $action();
        }

        // Если основное действие маршрута вернуло еще одну
        // функцию (например, перенаправление), то вызовем её
        if (is_callable($result)) {
            $result();
        }
    }

    /**
     * Ищет подходящий маршрут для переданного пути и возвращает соответствующую функцию-обработчик.
     *
     * @param string $path
     * @return callable
     */
    private function getHandler(string $path): callable
    {
        $foundRouteMethods = [];

        foreach ($this->routes as $route) {
            // В переданном пути пытаемя найти
            // соответствия по регулярному выражению
            $matches = $route->match($path);

            if (empty($matches)) {
                continue;
            }

            // Если маршрут был найден, но глагол запроса не совпадает с глаголами,
            // поддерживаемыми этим маршрутом, сохраняем их, чтобы вывести ошибку далее
            if (!in_array($_SERVER['REQUEST_METHOD'], $route->getMethods(), true)) {
                array_push($foundRouteMethods, ...$route->getMethods());
                continue;
            }

            // Удаляем полный путь из массива найденных соответствий,
            // так как нам нужны только параметры маршрута,
            // которые мы передадим в обработчик
            unset($matches[0]);

            return self::found($route, $matches);
        }

        if (!empty($foundRouteMethods)) {
            return self::invalidRequestMethod(array_unique($foundRouteMethods));
        }

        return self::notFound();
    }

    private static function invalidRequestMethod(array $expectedRequestMethods): callable
    {
        return static function () use ($expectedRequestMethods) {
            printf(
                'Некорректный глагол запроса: %s. Разрешены следующие: %s.',
                $_SERVER['REQUEST_METHOD'],
                implode(', ', $expectedRequestMethods)
            );
        };
    }

    /**
     * Возвращает функцию-обработчик найденного маршрута.
     *
     * @param Route $route
     * @param array $params
     *
     * @return callable
     */
    private static function found(Route $route, array $params): callable
    {
        return static function () use ($route, $params) {
            // Вызываем обработчики маршрута,
            // которые должны выполниться перед непосредственным действием
            foreach ($route->getBefore() as $before) {
                if ($before() === false) {
                    return null;
                }
            }

            // Выполняем главное действие маршрута, передавая в него найденные параметры
            $result = ($route->getAction())(...$params);

            // Выполняем остальные обработчики маршрута
            foreach ($route->getAfter() as $action) {
                $action();
            }

            if (is_callable($result)) {
                return $result;
            }

            return null;
        };
    }

    private static function notFound(): callable
    {
        return static function () {
            throw new \RuntimeException('Страница не найдена', 404);
        };
    }
}
