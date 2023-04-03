<?php

namespace App\Entity;

class Magic
{
    private int $id;
    private string $name;
    private ?string $description;

    private array $elems;

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
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param ?string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getElems(): array
    {
        return $this->elems;
    }

    /**
     * @param int $index
     * @return Elem
     */
    public function getElemAtIndex(int $index): Elem {
        return $this->elems[$index];
    }

    /**
     * @param array $elems
     */
    public function setElems(array $elems): void
    {
        $this->elems = $elems;
    }

    /**
     * @param Elem $elem
     * @return void
     */
    public function addElem(Elem $elem): void {
        $this->elems[] = $elem;
    }
}