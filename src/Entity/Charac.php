<?php

namespace App\Entity;

use DateTime;

class Charac
{
    private int $id;
    private string $lastNames;
    private string $firstNames;
    private ?string $portrait;
    private DateTime $birthdate;
    private ?DateTime $deathdate;

    private ?_Class $class;
    private Calendar $calendar;
    private ?Nation $origin;
    private array $magics;

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
    public function getLastNames(): string
    {
        return $this->lastNames;
    }

    /**
     * @param string $lastNames
     */
    public function setLastNames(string $lastNames): void
    {
        $this->lastNames = $lastNames;
    }

    /**
     * @return string
     */
    public function getFirstNames(): string
    {
        return $this->firstNames;
    }

    /**
     * @param string $firstNames
     */
    public function setFirstNames(string $firstNames): void
    {
        $this->firstNames = $firstNames;
    }

    /**
     * @return ?string
     */
    public function getPortrait(): ?string
    {
        return $this->portrait;
    }

    /**
     * @param ?string $portrait
     */
    public function setPortrait(?string $portrait): void
    {
        $this->portrait = $portrait;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $birthdate
     */
    public function setBirthdate(DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return ?DateTime
     */
    public function getDeathdate(): ?DateTime
    {
        return $this->deathdate;
    }

    /**
     * @param ?DateTime $deathdate
     */
    public function setDeathdate(?DateTime $deathdate): void
    {
        $this->deathdate = $deathdate;
    }

    /**
     * @return ?_Class
     */
    public function getClass(): ?_Class
    {
        return $this->class;
    }

    /**
     * @param ?_Class $class
     */
    public function setClass(?_Class $class): void
    {
        $this->class = $class;
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

    /**
     * @return Nation
     */
    public function getOrigin(): Nation
    {
        return $this->origin;
    }

    /**
     * @param Nation $origin
     */
    public function setOrigin(Nation $origin): void
    {
        $this->origin = $origin;
    }

    /**
     * @return array
     */
    public function getMagics(): array
    {
        return $this->magics;
    }

    /**
     * @param array $magics
     */
    public function setMagics(array $magics): void
    {
        $this->magics = $magics;
    }
}