<?php

namespace App\Entity;

class CharacRelationship
{
    private int $id;
    private Charac $fromCharac;
    private Charac $towardsCharac;
    private ?FamilyStatus $familyStatus;
    private int $appreciation;
    private ?string $history;

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
     * @return Charac
     */
    public function getFromCharac(): Charac
    {
        return $this->fromCharac;
    }

    /**
     * @param Charac $fromCharac
     */
    public function setFromCharac(Charac $fromCharac): void
    {
        $this->fromCharac = $fromCharac;
    }

    /**
     * @return Charac
     */
    public function getTowardsCharac(): Charac
    {
        return $this->towardsCharac;
    }

    /**
     * @param Charac $towardsCharac
     */
    public function setTowardsCharac(Charac $towardsCharac): void
    {
        $this->towardsCharac = $towardsCharac;
    }

    /**
     * @return ?FamilyStatus
     */
    public function getFamilyStatus(): ?FamilyStatus
    {
        return $this->familyStatus;
    }

    /**
     * @param ?FamilyStatus $familyStatus
     */
    public function setFamilyStatus(?FamilyStatus $familyStatus): void
    {
        $this->familyStatus = $familyStatus;
    }

    /**
     * @return int
     */
    public function getAppreciation(): int
    {
        return $this->appreciation;
    }

    /**
     * @param int $appreciation
     */
    public function setAppreciation(int $appreciation): void
    {
        $this->appreciation = $appreciation;
    }

    /**
     * @return ?string
     */
    public function getHistory(): ?string
    {
        return $this->history;
    }

    /**
     * @param ?string $history
     */
    public function setHistory(?string $history): void
    {
        $this->history = $history;
    }
}