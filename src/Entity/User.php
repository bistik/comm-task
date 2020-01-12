<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

abstract class User
{
    /** @var int */
    private $id;

    /**
     * User constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;
}
