<?php

namespace App\Entity;

use DateTime;

class CharacEvent
{
    private int $id;
    private DateTime $startingDate;
    private DateTime $endingDate;
    private string $description;

    private Charac $charac;

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
     * @return DateTime
     */
    public function getStartingDate(): DateTime
    {
        return $this->startingDate;
    }

    /**
     * @param DateTime $startingDate
     */
    public function setStartingDate(DateTime $startingDate): void
    {
        $this->startingDate = $startingDate;
    }

    /**
     * @return DateTime
     */
    public function getEndingDate(): DateTime
    {
        return $this->endingDate;
    }

    /**
     * @param DateTime $endingDate
     */
    public function setEndingDate(DateTime $endingDate): void
    {
        $this->endingDate = $endingDate;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Charac
     */
    public function getCharac(): Charac
    {
        return $this->charac;
    }

    /**
     * @param Charac $charac
     */
    public function setCharac(Charac $charac): void
    {
        $this->charac = $charac;
    }
}