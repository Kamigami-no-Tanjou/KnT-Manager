<?php

namespace App\Entity;

use DateTime;

class Charac
{
    private int $id;
    private string $lastNames;
    private string $firstNames;
    private ?string $portrait;
    private ?string $description;
    private Calendar $calendar;
    private DateTime $birthdate;
    private ?DateTime $deathdate;

    private ?int $magicalPotential;
    private ?_Class $class;
    private Sexes $sex;
    private Genders $gender;
    private ?string $sexualOrientation;
    private ?Nation $origin;

    private ?int $height;
    private ?string $hairColour;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getMagicalPotential(): ?int
    {
        return $this->magicalPotential;
    }

    /**
     * @param int|null $magicalPotential
     */
    public function setMagicalPotential(?int $magicalPotential): void
    {
        $this->magicalPotential = $magicalPotential;
    }

    /**
     * @return Sexes
     */
    public function getSex(): Sexes
    {
        return $this->sex;
    }

    /**
     * @param Sexes $sex
     */
    public function setSex(Sexes $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return Genders
     */
    public function getGender(): Genders
    {
        return $this->gender;
    }

    /**
     * @param Genders $gender
     */
    public function setGender(Genders $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string|null
     */
    public function getSexualOrientation(): ?string
    {
        return $this->sexualOrientation;
    }

    /**
     * @param string|null $sexualOrientation
     */
    public function setSexualOrientation(?string $sexualOrientation): void
    {
        $this->sexualOrientation = $sexualOrientation;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     */
    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return string|null
     */
    public function getHairColour(): ?string
    {
        return $this->hairColour;
    }

    /**
     * @param string|null $hairColour
     */
    public function setHairColour(?string $hairColour): void
    {
        $this->hairColour = $hairColour;
    }

    /**
     * @return string|null
     */
    public function getEyeColour(): ?string
    {
        return $this->eyeColour;
    }

    /**
     * @param string|null $eyeColour
     */
    public function setEyeColour(?string $eyeColour): void
    {
        $this->eyeColour = $eyeColour;
    }

    /**
     * @return string|null
     */
    public function getAppearance(): ?string
    {
        return $this->appearance;
    }

    /**
     * @param string|null $appearance
     */
    public function setAppearance(?string $appearance): void
    {
        $this->appearance = $appearance;
    }

    /**
     * @return string|null
     */
    public function getFavoriteColour(): ?string
    {
        return $this->favoriteColour;
    }

    /**
     * @param string|null $favoriteColour
     */
    public function setFavoriteColour(?string $favoriteColour): void
    {
        $this->favoriteColour = $favoriteColour;
    }

    /**
     * @return string|null
     */
    public function getThingsLoved(): ?string
    {
        return $this->thingsLoved;
    }

    /**
     * @param string|null $thingsLoved
     */
    public function setThingsLoved(?string $thingsLoved): void
    {
        $this->thingsLoved = $thingsLoved;
    }

    /**
     * @return string|null
     */
    public function getThingsHated(): ?string
    {
        return $this->thingsHated;
    }

    /**
     * @param string|null $thingsHated
     */
    public function setThingsHated(?string $thingsHated): void
    {
        $this->thingsHated = $thingsHated;
    }
    private ?string $eyeColour;
    private ?string $appearance;

    private ?string $favoriteColour;
    private ?string $thingsLoved;
    private ?string $thingsHated;

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