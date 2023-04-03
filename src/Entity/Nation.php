<?php

namespace App\Entity;

use DateTime;

class Nation
{
    private int $id;
    private string $name;
    private Calendar $calendar;
    private DateTime $foundationDate;
    private ?DateTime $destructionDate;
    private string $description;

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
     * @return DateTime
     */
    public function getFoundationDate(): DateTime
    {
        return $this->foundationDate;
    }

    /**
     * @param DateTime $foundationDate
     */
    public function setFoundationDate(DateTime $foundationDate): void
    {
        $this->foundationDate = $foundationDate;
    }

    /**
     * @return ?DateTime
     */
    public function getDestructionDate(): ?DateTime
    {
        return $this->destructionDate;
    }

    /**
     * @param ?DateTime $destructionDate
     */
    public function setDestructionDate(?DateTime $destructionDate): void
    {
        $this->destructionDate = $destructionDate;
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
     * @return Calendar
     */
    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function setCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }
}