<?php namespace App\Services;

class Entity
{
    /** @var string */
    private $name;

    /** @var Entity */
    private $parent;

    /**
     * Entity constructor.
     * @param string $name
     * @param Entity|null $parent
     */
    public function __construct(string $name, Entity $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Entity|null
     */
    public function getParent() : ?Entity
    {
        return $this->parent;
    }

    public function setParent(Entity $parent) : void
    {
        $this->parent = $parent;
    }
}
