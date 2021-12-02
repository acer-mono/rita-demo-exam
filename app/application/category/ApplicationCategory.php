<?php
declare(strict_types=1);

final class ApplicationCategory
{
    private $id;
    private $name;

    public function __construct(string $name, int $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name)
    {
        $this->name = $name;
    }
}
