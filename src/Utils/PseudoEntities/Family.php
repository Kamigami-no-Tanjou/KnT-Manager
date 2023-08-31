<?php

namespace App\Utils\PseudoEntities;

use App\Entity\Charac;

class Family
{
    private string $familyId;
    private ?Charac $husband;
    private ?Charac $wife;
    private array $children;

    /**
     * @return string
     */
    public function getFamilyId(): string
    {
        return $this->familyId;
    }

    /**
     * @param string $familyId
     */
    public function setFamilyId(string $familyId): void
    {
        $this->familyId = $familyId;
    }

    /**
     * @return Charac|null
     */
    public function getHusband(): ?Charac
    {
        return $this->husband;
    }

    /**
     * @param Charac|null $husband
     */
    public function setHusband(?Charac $husband): void
    {
        $this->husband = $husband;
    }

    /**
     * @return Charac|null
     */
    public function getWife(): ?Charac
    {
        return $this->wife;
    }

    /**
     * @param Charac|null $wife
     */
    public function setWife(?Charac $wife): void
    {
        $this->wife = $wife;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }
}