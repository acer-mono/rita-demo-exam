<?php
declare(strict_types=1);

final class Route
{
    private $methods;
    private $pattern;
    private $action;
    private $before = [];
    private $after = [];

    /**
     * @param string|array $methods Глаголы запроса (GET, POST и т.д.)
     * @param string $pattern Регулярное выражение относительного пути маршрута
     * @param callable $action Действие, которое необходимо выполнить
     */
    public function __construct($methods, string $pattern, callable $action)
    {
        $this->setMethods($methods);
        $this->pattern = $pattern;
        $this->action = $action;
    }

    private function setMethods($methods)
    {
        if ($methods === 'ANY') {
            $this->methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
            return;
        }

        $this->methods = is_string($methods) ? [$methods] : $methods;
    }

    /**
     * Сопоставляет переданный путь с регулярным выражением на основе паттерна этого маршрута.
     *
     * @param string $path Путь из запроса к серверу
     * @return array Набор соответствий переданного пути регулярному выражению
     */
    public function match(string $path): array
    {
        preg_match($this->createRegex(), $path, $matches);

        if (empty($matches)) {
            return [];
        }

        return $matches;
    }

    /**
     * Создает полноценное регулярное выражение на основе паттерна этого маршрута.
     *
     * @return string
     */
    private function createRegex(): string
    {
        // Экранируем слеши в пути, чтобы они корректно
        // обрабатывались регулярным выражением
        $pattern = str_replace('/', '\/', $this->pattern);

        return sprintf('/^%s$/u', $pattern);
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getAction(): callable
    {
        return $this->action;
    }

    /**
     * @return callable[]
     */
    public function getBefore(): array
    {
        return $this->before;
    }

    /**
     * Добавляет действие, которое должно быть выполнено перед основным действием этого маршрута.
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
     * @return callable[]
     */
    public function getAfter(): array
    {
        return $this->after;
    }

    /**
     * Добавляет действие, которое должно быть выполнено после основного действия этого маршрута.
     *
     * @param callable $action
     * @return $this
     */
    public function addAfter(callable $action): self
    {
        $this->after[] = $action;
        return $this;
    }
}
