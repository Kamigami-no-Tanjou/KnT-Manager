<?php

namespace App\Entity;

class Elem
{
    private int $id;
    private string $name;
    private string $illustration;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIllustration(): string
    {
        return $this->illustration;
    }

    /**
     * @param string $illustration
     */
    public function setIllustration(string $illustration): void
    {
        $this->illustration = $illustration;
    }


}